<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\Message;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\MessageController
 */
class MessageControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function store_saves()
    {
        $user = User::factory()->create();
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
        ]);
        $toPhone = $this->faker->e164PhoneNumber();
        $contact = Contact::factory()->create([
            'user_id' => $user->id,
            'phone_numbers' => ['mobile' => $toPhone],
        ]);
        $body = 'foo bar biz bang';

        $response = $this->actingAs($user)->postJson(route('messages.store'), [
            'from' => $number->phone_number,
            'to' => $toPhone,
            'body' => $body,
            'direction' => Message::DIRECTION_OUT,
            'status' => Message::STATUS_LOCAL_CREATED,
            'num_media' => 0,
        ]);

        $messages = $user->messages()
            ->where('from', $number->phone_number)
            ->where('to', $toPhone)
            ->where('body', $body)
            ->get();

        $this->assertCount(1, $messages);
        $message = $messages->first();

        $response->assertCreated();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data')
                ->has('data.id')
                ->has('data.number_id')
                ->has('data.service_account_id')
                ->has('data.contact.id')
                ->has('data.contact.first_name')
                ->has('data.contact.last_name')
                ->has('data.contact.company')
                ->has('data.contact.phone_numbers')
                ->has('data.from')
                ->has('data.to')
                ->has('data.body')
                ->has('data.error_code')
                ->has('data.error_message')
                ->has('data.direction')
                ->has('data.status')
                ->has('data.num_media')
                ->has('data.media')
                ->has('data.external_identity')
                ->has('data.read')
        );
    }

    /**
     * @test
     */
    public function store_saves_without_contact()
    {
        $user = User::factory()->create();
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
        ]);
        $toPhone = $this->faker->e164PhoneNumber();
        $body = 'foo bar biz bang';

        $response = $this->actingAs($user)->postJson(route('messages.store'), [
            'from' => $number->phone_number,
            'to' => $toPhone,
            'body' => $body,
            'direction' => Message::DIRECTION_OUT,
            'status' => Message::STATUS_LOCAL_CREATED,
            'num_media' => 0,
        ]);

        $messages = $user->messages()
            ->where('from', $number->phone_number)
            ->where('to', $toPhone)
            ->where('body', $body)
            ->get();

        $this->assertCount(1, $messages);
        $message = $messages->first();

        $response->assertCreated();
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('data')
            ->where('data.contact', null)
            ->etc()
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $user = User::factory()->create();
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
        ]);
        $toPhone = $this->faker->e164PhoneNumber();
        $body = 'foo bar biz bang';

        $message = Message::factory()->create([
            'user_id' => $user->id,
            'number_id' => $number->id,
            'read' => false,
            'from' => $number->phone_number,
            'to' => $toPhone,
            'body' => $body,
        ]);
        $this->assertFalse($message->read);

        $response = $this->actingAs($user)->putJson(route('messages.update', $message), [
            'read' => true,
        ]);

        $response->assertOk();

        $message->refresh();
        $this->assertTrue($message->read);
    }
}
