<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Message;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Facade\Ignition\Support\FakeComposer;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class ThreadSeeder extends Seeder
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::whereEmail('test@test.com')->first();
        $serviceAccount = ServiceAccount::factory()->create(['user_id' => $user->id]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
            'friendly_label' => 'Thread Number',
        ]);
        $myNumber = $number->phone_number;

        //outbound message w/ no response
        Message::factory()->create([
            'contact_id' => null,
            'number_id' => $number->id,
            'user_id' => $user->id,
            'from' => $myNumber,
            'to' => $this->faker->e164PhoneNumber(),
            'body' => 'to outer space',
            'direction' => Message::DIRECTION_OUT,
            'created_at' => now()->subDays(4)->subSecond(rand(0, 59)),
        ]);

        //inbound message w/ no response
        Message::factory()->create([
            'contact_id' => null,
            'number_id' => $number->id,
            'user_id' => $user->id,
            'from' => $this->faker->e164PhoneNumber(),
            'to' => $myNumber,
            'body' => 'fancy some spam?',
            'direction' => Message::DIRECTION_IN,
            'created_at' => now()->subDays(5)->subSeconds(rand(0, 59)),
            'read' => false,
        ]);

        //create a message thread that has no contact
        $this->seedThread($user, false, 10, 7);

        //create 5 contacts, each with 10 messages
        foreach (range(0, 4) as $c) {
            $this->seedThread($user, true, 10);
        }
    }//end run()

    /**
     * Function to seed a message thread creating all the associated
     * relationship objects.
     *
     * @param User $user
     * @param bool $withContact - create contact or not
     * @param int $messageCount - messages to create
     * @param int $subDays - stand off created_at for messages
     * @return string - phone number interacted with
     */
    public function seedThread(User $user, $withContact = false, $messageCount = 2, $subDays = 10)
    {
        $serviceAccount = ServiceAccount::factory()->create(['user_id' => $user->id]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
        ]);
        $myNumber = $number->phone_number;
        $toNumber = $this->faker->e164PhoneNumber();
        $contactId = null;

        if ($withContact) {
            $contact = Contact::factory()->create([
                'user_id' => $user->id,
                'phone_numbers' => ['mobile' => $toNumber],
            ]);
            $contactId = $contact->id;
        }

        foreach (range(0, ($messageCount - 1)) as $key) {
            if ($key % 2 == 0) {
                $direction = Message::DIRECTION_IN;
                $from = $toNumber;
                $to = $myNumber;
            } else {
                $direction = Message::DIRECTION_OUT;
                $from = $myNumber;
                $to = $toNumber;
            }

            Message::factory()->create([
                'contact_id' => $contactId,
                'number_id' => $number->id,
                'user_id' => $user->id,
                'from' => $from,
                'to' => $to,
                'body' => $this->faker->words(7, true),
                'media' => [],
                'direction' => $direction,
                'created_at' => now()->subDays($subDays)->addMinutes($key)->addSeconds(rand(0, 59)),
                'read' => $direction == Message::DIRECTION_IN ? false : true,
            ]);
        }

        return $toNumber;
    }
}
