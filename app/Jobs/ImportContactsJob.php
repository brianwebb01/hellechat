<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JeroenDesloovere\VCard\VCardParser;

class ImportContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The type that should be given
     * for phone numbers where the
     * type is unknown and not in the
     * list of replacements.
     */
    public const OTHER_TYPE = 'other';

    /**
     * The user the imported contacts
     * should be attached to
     *
     * @var App\Models\User
     */
    public $user;

    /**
     * Path to the file who's contents
     * should be read in and processed
     *
     * @var string
     */
    public $filepath;

    /**
     * Batch size of contact records to
     * make before bulk saving
     *
     * @var integer
     */
    public $batchSize = 25;

    /**
     * temporary store of contact records to
     * be saved.  Added to until process is
     * complete or the batch size is hit
     *
     * @var array
     */
    public $contacts = [];

    /**
     * Dictionary of supported phone
     * types
     *
     * @var array
     */
    public $phoneTypes = [
        "mobile",
        "home",
        "work",
        "office",
        "school",
        "main",
        "fax",
        "pager",
        "other"
    ];

    /**
     * Dictionary of unsupported phone
     * types and their replacements
     *
     * @var array
     */
    public $phoneTypeConversions = [
        'pref' => 'main',
        'cell' => 'mobile',
        'iphone' => 'mobile',
        'default' => 'main'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $filepath)
    {
        $this->user = $user;
        $this->filepath = $filepath;
    }

    /**
     * Execute the job by reading the consturctor given
     * file, then passing the contents to other functions
     * for processing.  Deletes file after complete
     *
     * @return void
     */
    public function handle()
    {
        Log::debug("Import file: {$this->filepath}");
        $content = Storage::get($this->filepath);
        $this->createContactsFromVCardExport($content);
        Storage::delete($this->filepath);
    }

    /**
     * Function to loop over the VCF data and save it to the database
     *
     * @param string $content
     * @return void
     */
    public function createContactsFromVCardExport($content)
    {
        $parser = app()->makeWith(VCardParser::class, ['content' => $content]);

        foreach($parser as $vcard){
            $contact = app(Contact::class);

            if(property_exists($vcard, 'firstname') && !empty($vcard->firstname))
                $contact->first_name = $this->clean($vcard->firstname);

            if(property_exists($vcard, 'lastname') && !empty($vcard->lastname))
                $contact->last_name = $this->clean($vcard->lastname);

            if(property_exists($vcard, 'organization') && !empty($vcard->organization))
                $contact->company = $this->clean($vcard->organization);

            if( !$contact->first_name &&
                !$contact->last_name &&
                !$contact->company
            ){
                continue;
            }

            $phone_numbers = [];

            if(property_exists($vcard, 'phone')){
                foreach($vcard->phone as $vPhoneType => $number){
                    $tempType = \strtolower(explode(';', $vPhoneType)[0]);
                    $tempNum = preg_replace('/[^0-9,.]+/', '', $number)[0];

                    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                    try {
                        $numberProto = $phoneUtil->parse($tempNum, config('app.phone_country'));
                        $e164Phone = $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::E164);
                    } catch (\libphonenumber\NumberParseException $e) {
                        Log::error("Phone number parse error for {$tempNum} with message: ". $e->getMessage());
                        continue;
                    }


                    if(in_array($tempType, $this->phoneTypes)){
                        $type = $tempType;
                    } else if(\array_key_exists($tempType, $this->phoneTypes)){
                        $type = $this->phoneTypes[$tempType];
                    } else {
                        $type = static::OTHER_TYPE;
                    }

                    //@TODO look for existing type in the array already?
                    $phone_numbers[$type] = $e164Phone;
                }
            }

            $contact->phone_numbers = $phone_numbers;

            if(!$this->duplicateExists($contact))
                $this->contacts[] = $contact;

            if(count($this->contacts) >= $this->batchSize){
                $this->user->contacts()->saveMany($this->contacts);
                $this->contacts = [];
            }

        }//end foreach

        if (count($this->contacts) > 0) {
            $this->user->contacts()->saveMany($this->contacts);
        }
    }


    /**
     * Function to clean the VCF string and remove
     * any erroneous characters etc.
     *
     * @param string $input
     * @return string
     */
    public function clean($input)
    {
        $output = $input;

        if(substr($output, -1) == ';'){
            return $this->clean(substr($output, 0, \strlen($output) - 1) );
        } else {
            return $output;
        }
    }


    /**
     * Function to determine if the given contact
     * exists either in the database or in the buffer
     *
     * @param Contact $contact
     * @return boolean
     */
    public function duplicateExists(Contact $contact)
    {
        $isInBuffer = collect($this->contacts)->contains(fn($c) =>
            $c->first_name == $contact->first_name &&
            $c->last_name == $contact->last_name &&
            $c->company == $contact->company &&
            $c->phone_numbers == $contact->phone_numbers
        );

        if($isInBuffer) return true;

        return Contact::where('first_name', $contact->first_name)
            ->where('last_name', $contact->last_name)
            ->where('company', $contact->company)
            ->whereJsonContains('phone_numbers', $contact->phone_numbers)
            ->count() > 0;
    }
}
