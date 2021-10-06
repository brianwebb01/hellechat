<?php

namespace Database\Seeders;

use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create(['email' => 'test@test.com']);
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
            'name' => 'testing acct',
            'provider' => 'twilio',
            'api_key' => config('services.twilio.account_sid'),
            'api_secret' => config('services.twilio.auth_token')
        ]);
        $sa2 = ServiceAccount::factory()->create([
            'user_id' => $user->id,
            'name' => 'test 2',
            'provider' => 'telnyx'
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
            'friendly_label' => 'twilio testing number',
            'phone_number' => '+15024105645',
            'sip_registration_url' => '5024105645@5024105645.sip.us1.twilio.com',
            'external_identity' => 'PN6073db21e05003438a7c4340457ac090'
        ]);

        User::factory()->count(4)->create();
    }
}
