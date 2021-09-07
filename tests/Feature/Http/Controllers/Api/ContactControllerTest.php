<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ContactController
 */
class ContactControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        Contact::factory()->count(5)->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->getJson(route('contact.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function index_respects_authorization()
    {
        Contact::factory()->count(5)->create(['user_id' => $this->user->id]);
        Contact::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->getJson(route('contact.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);

        $userIds = collect($response->json("data"))->pluck('user_id')->unique();
        $this->assertCount(1, $userIds);
        $this->assertEquals($this->user->id, $userIds->first());
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\ContactController::class,
            'store',
            \App\Http\Requests\Api\ContactStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $fName = $this->faker->firstName;
        $lName = $this->faker->lastName;
        $phone_numbers = collect(['mobile', 'home', 'office', 'work', 'main'])
            ->random(rand(0, 3))
            ->map(fn ($i) => [$i => $this->faker->e164PhoneNumber])
            ->flatMap(fn ($i) => $i)
            ->toArray();

        $response = $this->actingAs($this->user)->postJson(route('contact.store'), [
            'first_name' => $fName,
            'last_name' => $lName,
            'phone_numbers' => json_encode($phone_numbers),
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $contacts = $this->user->contacts()
            ->where('first_name', $fName)
            ->where('last_name', $lName)
            ->get();
        $this->assertCount(1, $contacts);

        $contact = $contacts->first();
        $this->assertEquals(json_encode($phone_numbers), $contact->phone_numbers);
        $response->assertJson(fn(AssertableJson $json) =>
            $json->where('data.first_name', $fName)
                 ->where('data.last_name', $lName)
                 ->where('data.user_id', $this->user->id)
                 ->etc()
        );
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson(route('contact.show', $contact));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('contact.show', $contact));

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\ContactController::class,
            'update',
            \App\Http\Requests\Api\ContactUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $fName = $this->faker->firstName;
        $lName = $this->faker->lastName;
        $phone_numbers = collect(['mobile', 'home', 'office', 'work', 'main'])
            ->random(rand(0, 3))
            ->map(fn ($i) => [$i => $this->faker->e164PhoneNumber])
            ->flatMap(fn ($i) => $i)
            ->toArray();

        $response = $this->actingAs($this->user)->putJson(route('contact.update', $contact), [
            'first_name' => $fName,
            'last_name' => $lName,
            'phone_numbers' => json_encode($phone_numbers),
        ]);

        $response->assertOk();
        $response->assertJsonStructure([]);

        $contact->refresh();

        $this->assertEquals($fName, $contact->first_name);
        $this->assertEquals($lName, $contact->last_name);
        $this->assertEquals(json_encode($phone_numbers), $contact->phone_numbers);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('data.first_name', $fName)
                ->where('data.last_name', $lName)
                ->where('data.user_id', $this->user->id)
                ->etc()
        );
    }

    /**
     * @test
     */
    public function update_respects_auth_policy()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->putJson(route('contact.update', $contact), [
            'first_name' => 'foo',
        ]);

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson(route('contact.destroy', $contact));

        $response->assertNoContent();

        $this->assertDeleted($contact);
    }


    /**
     * @test
     */
    public function destroy_respects_auth_policy()
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('contact.show', $contact));

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }
}
