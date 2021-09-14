<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Contact;
use App\Models\User;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'company' => $this->faker->company,
            'phone_numbers' => collect(['mobile', 'home', 'office', 'work', 'main'])
                ->random(rand(0, 3))
                ->map(fn ($i) => [$i => $this->faker->e164PhoneNumber()])
                ->flatMap(fn ($i) => $i)
                ->toArray(),
        ];
    }
}
