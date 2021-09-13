<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Contact;
use App\Models\Number;
use App\Models\User;
use App\Models\Voicemail;

class VoicemailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voicemail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'number_id' => Number::factory(),
            'contact_id' => Contact::factory(),
            'media_url' => $this->faker->word,
            'length' => $this->faker->randomNumber(2),
            'transcription' => $this->faker->text,
            'from' => $this->faker->e164PhoneNumber
        ];
    }
}
