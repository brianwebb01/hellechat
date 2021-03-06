<?php

namespace Database\Factories;

use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $n = $this->faker->e164PhoneNumber();

        return [
            'user_id' => User::factory(),
            'service_account_id' => ServiceAccount::factory(),
            'phone_number' => $n,
            'sip_registration_url' => "{$n}@{$n}.sip.somewhere.com",
            'friendly_label' => $this->faker->words(2, true),
            'external_identity' => $this->faker->regexify('[A-Za-z0-9]{24}'),
            'dnd_calls' => false,
            'dnd_voicemail' => false,
            'dnd_messages' => false,
            'dnd_allow_contacts' => false,
        ];
    }
}
