<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $mediaArray = collect(rand(0,2))->map(fn($i) => $this->faker->imageUrl())->toArray();
        return [
            'user_id' => User::factory(),
            'number_id' => Number::factory(),
            'service_account_id' => ServiceAccount::factory(),
            'contact_id' => Contact::factory(),
            'from' => $this->faker->e164PhoneNumber(),
            'to' => $this->faker->e164PhoneNumber(),
            'body' => $this->faker->text,
            'error_code' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'error_message' => $this->faker->text,
            'direction' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'status' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'num_media' => count($mediaArray),
            'media' => $mediaArray,
            'external_identity' => $this->faker->word,
            'external_date_created' => $this->faker->dateTime(),
            'external_date_updated' => $this->faker->dateTime(),
            'read' => true
        ];
    }
}
