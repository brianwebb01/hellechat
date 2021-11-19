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
use Illuminate\Support\Facades\Storage;
use JeroenDesloovere\VCard\VCardParser;

class ImportContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const OTHER_TYPE = 'other';

    public $user;

    public $filepath;

    public $batchSize = 25;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = Storage::get($this->filepath);
        $this->createContactsFromVCardExport($content);
        Storage::delete($this->filepath);
    }

    public function createContactsFromVCardExport($content)
    {
        $parser = app()->makeWith(VCardParser::class, ['content' => $content]);
        $contacts = [];

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
                    $e164Phone = '+1' . preg_replace('/[^0-9,.]+/', '', $number)[0];

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
            $contacts[] = $contact;

            if(count($contacts) >= $this->batchSize){
                $this->user->contacts()->saveMany($contacts);
                $contacts = [];
            }

        }//end foreach

        if (count($contacts) > 0) {
            $this->user->contacts()->saveMany($contacts);
        }
    }

    public function clean($input)
    {
        $output = $input;

        if(substr($output, -1) == ';'){
            return $this->clean(substr($output, 0, \strlen($output) - 1) );
        } else {
            return $output;
        }
    }
}
