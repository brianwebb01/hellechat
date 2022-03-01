<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Number;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $user = User::factory()->create();
        $number = Number::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('user'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data.id')
            ->has('data.hash_id')
            ->has('data.email')
            ->has('data.twilio_messaging_endpoint')
            ->where(
                "data.twilio_voice_endpoints.{$number->phone_number}",
                route('webhooks.twilio.voice', [
                    'userHashId' => $user->getHashId(),
                    'numberHashId' => $number->getHashId(),
                ])
            )->has('data.created_at')
        );
    }
}
