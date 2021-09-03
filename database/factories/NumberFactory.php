<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;

class NumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Number::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'service_account_id' => ServiceAccount::factory(),
            'phone_number' => $this->faker->e164PhoneNumber,
            'friendly_label' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'external_identity' => $this->faker->word,
        ];
    }
}
