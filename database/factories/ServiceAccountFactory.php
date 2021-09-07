<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ServiceAccount;
use App\Models\User;

class ServiceAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'provider' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'api_key' => $this->faker->word,
            'api_secret' => $this->faker->word,
        ];
    }
}
