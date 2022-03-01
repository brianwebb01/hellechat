<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $coRand = rand(0, 2);
        if ($coRand == 2) {
            $first_name = null;
            $last_name = null;
            $company = $this->faker->company;
        } else {
            $first_name = $this->faker->firstName;
            $last_name = rand(0, 4) == 0 ? null : $this->faker->lastName;
            $company = rand(0, 1) == 0 ? $this->faker->company : null;
        }

        return [
            'user_id' => User::factory(),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'company' => $company,
            'phone_numbers' => collect(['mobile', 'home', 'office', 'work', 'main'])
                ->random(rand(0, 3))
                ->map(fn ($i) => [$i => $this->faker->e164PhoneNumber()])
                ->flatMap(fn ($i) => $i)
                ->toArray(),
        ];
    }
}
