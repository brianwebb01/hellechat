<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ContactSearchControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function search_respects_user_ownership()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        Contact::factory()->create([
            'user_id' => $userA->id,
            'first_name' => 'la-foobar',
        ]);
        Contact::factory()->create([
            'user_id' => $userB->id,
            'first_name' => 'el-foobar',
        ]);

        $response = $this->actingAs($userA)->postJson(route('contacts.search'), [
            'query' => 'foo',
        ]);

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
                ->has('data.0', fn ($json) => $json->where('first_name', 'la-foobar')
                        ->etc()
                )
        );
    }

    /** @test */
    public function search_queries_expected_attributes()
    {
        $user = User::factory()->create();
        Contact::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'a-9988-foobar',
        ]);
        Contact::factory()->create([
            'user_id' => $user->id,
            'last_name' => 'b-9988-foobar',
        ]);
        Contact::factory()->create([
            'user_id' => $user->id,
            'company' => 'c-9988-foobar',
        ]);
        Contact::factory()->create([
            'user_id' => $user->id,
            'phone_numbers' => json_encode(['mobile' => '+15029998888']),
        ]);

        $response = $this->actingAs($user)->postJson(route('contacts.search'), [
            'query' => '9988',
        ]);

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
        );
        $response->assertJsonFragment(['first_name' => 'a-9988-foobar']);
        $response->assertJsonFragment(['last_name' => 'b-9988-foobar']);
        $response->assertJsonFragment(['company' => 'c-9988-foobar']);
        $response->assertJsonFragment(['phone_numbers' => '{"mobile":"+15029998888"}']);
    }
}
