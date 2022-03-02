<?php

namespace Database\Seeders;

use App\Jobs\CreateGotifyUserRecordsJob;
use App\Models\Contact;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use App\Models\Voicemail;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class UserSeeder extends Seeder
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

        $gotify = new CreateGotifyUserRecordsJob(new User);
        $user = User::factory()->create([
            'name' => 'tester',
            'email' => 'test@test.com',
            'gotify_user_name' => $gotify->generateUserName(),
            'gotify_user_pass' => $gotify->generateUserPass(),
        ]);
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
            'name' => 'testing acct',
            'provider' => 'twilio',
            'api_key' => config('services.twilio.account_sid'),
            'api_secret' => config('services.twilio.auth_token'),
        ]);
        $sa2 = ServiceAccount::factory()->create([
            'user_id' => $user->id,
            'name' => 'test 2',
            'provider' => 'telnyx',
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
            'friendly_label' => 'twilio testing number',
            'phone_number' => '+15024105645',
            'sip_registration_url' => '5024105645@5024105645.sip.us1.twilio.com',
            'external_identity' => 'PN6073db21e05003438a7c4340457ac090',
        ]);

        Contact::factory()->count(100)->create([
            'user_id' => $user->id,
        ]);

        foreach (range(1, 50) as $i) {
            $contact_id = null;
            $from = $this->faker->e164PhoneNumber();

            if ($i % 2 == 0) {
                $c = Contact::factory()->create([
                    'user_id' => $user->id,
                    'phone_numbers' => collect(['mobile', 'home', 'office', 'work', 'main'])
                    ->random(rand(1, 3))
                    ->map(fn ($i) => [$i => $this->faker->e164PhoneNumber()])
                    ->flatMap(fn ($i) => $i)
                    ->toArray(),
                ]);
                $contact_id = $c->id;
                $from = collect($c->phone_numbers)->values()->random(1)->first();
            }
            Voicemail::factory()->create([
                'number_id' => rand(0, 1) > 0 ? Number::factory() : $number->id,
                'user_id' => $user->id,
                'contact_id' => $contact_id,
                'from' => $from,
                'media_url' => 'https://api.twilio.com/cowbell.mp3',
                'length' => 52,
            ]);
        }

        User::factory()->count(4)->create();
    }
}
