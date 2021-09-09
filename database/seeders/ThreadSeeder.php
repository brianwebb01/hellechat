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

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setUpFaker();

        $user = User::factory()->create(['email' => 'user@threads.com']);
        $serviceAccount = ServiceAccount::factory()->create(['user_id' => $user->id]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
            'friendly_label' => 'Thread Number'
        ]);
        $myNumber = $number->phone_number;
        $randomNumber = $this->faker->e164PhoneNumber;

        //outbound message w/ no response
        Message::factory()->create([
            'contact_id' => null,
            'number_id' => $number->id,
            'user_id' => $user->id,
            'from' => $myNumber,
            'to' => $this->faker->e164PhoneNumber,
            'body' => 'to outer space',
            'direction' => 'outbound',
            'created_at' => now()->subDays(4)->subSecond(rand(0,59)),
        ]);

        //inbound message w/ no response
        Message::factory()->create([
            'contact_id' => null,
            'number_id' => $number->id,
            'user_id' => $user->id,
            'from' => $this->faker->e164PhoneNumber,
            'to' => $myNumber,
            'body' => 'fancy some spam?',
            'direction' => 'inbound',
            'created_at' => now()->subDays(5)->subSeconds(rand(0,59)),
        ]);

        //create a message thread that has no contact
        foreach(range(0,9) as $key){
            if ($key % 2 == 0) {
                $direction = 'inbound';
                $from = $randomNumber;
                $to = $myNumber;
            } else {
                $direction = 'outbound';
                $from = $myNumber;
                $to = $randomNumber;
            }

            Message::factory()->create([
                'contact_id' => null,
                'number_id' => $number->id,
                'user_id' => $user->id,
                'from' => $from,
                'to' => $to,
                'body' => str_split('abcdefghijklmnop')[$key],
                'direction' => $direction,
                'created_at' => now()->subDays(7)->addHours($key)->addSeconds(rand(0,59)),
            ]);
        }

        //create 5 contacts, each having a 10 message thread
        $contacts = collect([]);
        foreach(range(0,4) as $c){
            $contacts[] = Contact::factory()->create([
                'user_id' => $user->id,
                'phone_numbers' => ['mobile' => $this->faker->e164PhoneNumber]
            ]);
        }

        $contacts->each(function($contact) use($myNumber, $user, $number){
            foreach(range(0,9) as $key){
                if ($key % 2 == 0) {
                    $direction = 'inbound';
                    $from = $contact->phone_numbers['mobile'];
                    $to = $myNumber;
                } else {
                    $direction = 'outbound';
                    $from = $myNumber;
                    $to = $contact->phone_numbers['mobile'];
                }

                $contact->messages()->save(
                    Message::factory()->make([
                        'user_id' => $user->id,
                        'number_id' => $number->id,
                        'from' => $from,
                        'to' => $to,
                        'body' => str_split('ABCDEFGHIJKLMNOP')[$key],
                        'direction' => $direction,
                        'created_at' => now()->subDays(10)->addMinutes($key)->addSeconds(rand(0,59)),                        ])
                );
            }//end each 10
        });//end each contact
    }
}
