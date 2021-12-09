<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\NumberController
 */
class NumberControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $serviceAccount;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        Number::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'service_account_id' => $this->serviceAccount->id
        ]);

        $response = $this->actingAs($this->user)->getJson(route('numbers.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 5)
                ->has('data.0.id')
                ->has('data.0.user_id')
                ->has('data.0.service_account')
                ->has('data.0.service_account.provider')
                ->has('data.0.phone_number')
                ->has('data.0.friendly_label')
                ->has('data.0.external_identity')
                ->has('data.0.sip_registration_url')
                ->has('data.0.messaging_endpoint')
                ->has('data.0.voice_endpoint')
                ->has('data.0.dnd_calls')
                ->has('data.0.dnd_voicemail')
                ->has('data.0.dnd_messages')
                ->has('data.0.dnd_allow_contacts')
                ->has('links')
                ->has('links.first')
                ->has('links.last')
                ->has('links.prev')
                ->has('links.next')
                ->has('meta')
                ->has('meta.current_page')
                ->has('meta.from')
                ->has('meta.last_page')
                ->has('meta.links')
                ->has('meta.path')
                ->has('meta.per_page')
                ->has('meta.to')
                ->has('meta.total')
        );
    }



    /**
     * @test
     */
    public function store_saves()
    {
        $phone_number = $this->faker->e164PhoneNumber();
        $friendly_label = $this->faker->word;

        $response = $this->actingAs($this->user)->postJson(route('numbers.store'), [
            'service_account_id' => $this->serviceAccount->id,
            'phone_number' => $phone_number,
            'friendly_label' => $friendly_label,
        ]);

        $response->assertCreated();

        $numbers = $this->user->numbers()
            ->where('service_account_id', $this->serviceAccount->id)
            ->where('phone_number', $phone_number)
            ->where('friendly_label', $friendly_label)
            ->get();
        $this->assertCount(1, $numbers);

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.service_account')
                ->has('data.service_account.provider')
                ->has('data.phone_number')
                ->has('data.friendly_label')
                ->has('data.external_identity')
                ->has('data.sip_registration_url')
                ->has('data.messaging_endpoint')
                ->has('data.voice_endpoint')
                ->has('data.dnd_calls')
                ->has('data.dnd_voicemail')
                ->has('data.dnd_messages')
                ->has('data.dnd_allow_contacts')
        );
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $number = Number::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson(route('numbers.show', $number));

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.service_account')
                ->has('data.service_account.provider')
                ->has('data.phone_number')
                ->has('data.friendly_label')
                ->has('data.external_identity')
                ->has('data.sip_registration_url')
                ->has('data.messaging_endpoint')
                ->has('data.voice_endpoint')
                ->has('data.dnd_calls')
                ->has('data.dnd_voicemail')
                ->has('data.dnd_messages')
                ->has('data.dnd_allow_contacts')
        );
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $number = Number::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('numbers.show', $number));

        $response->assertForbidden();
    }



    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $number = Number::factory()->create(['user_id' => $this->user->id]);
        $phone_number = $this->faker->e164PhoneNumber();
        $friendly_label = $this->faker->word;
        $sip_registration_url = '555@444.sip.somewhere.com';

        $response = $this->actingAs($this->user)->putJson(route('numbers.update', $number), [
            'service_account_id' => $this->serviceAccount->id,
            'phone_number' => $phone_number,
            'friendly_label' => $friendly_label,
            'sip_registration_url' => $sip_registration_url
        ]);

        $number->refresh();

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.service_account')
                ->has('data.service_account.provider')
                ->has('data.phone_number')
                ->has('data.friendly_label')
                ->has('data.external_identity')
                ->has('data.sip_registration_url')
                ->has('data.messaging_endpoint')
                ->has('data.voice_endpoint')
                ->has('data.dnd_calls')
                ->has('data.dnd_voicemail')
                ->has('data.dnd_messages')
                ->has('data.dnd_allow_contacts')
        );

        $this->assertEquals($this->user->id, $number->user_id);
        $this->assertEquals($this->serviceAccount->id, $number->service_account_id);
        $this->assertEquals($phone_number, $number->phone_number);
        $this->assertEquals($friendly_label, $number->friendly_label);
        $this->assertEquals($sip_registration_url, $number->sip_registration_url);
    }

    /**
     * @test
     */
    public function update_respects_auth_policy()
    {
        $number = Number::factory()->create();

        $response = $this->actingAs($this->user)->putJson(route('numbers.update', $number), [
            'service_account_id' => 0,
        ]);
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function validation_respects_foreign_object_ownership()
    {
        $number = Number::factory()->create([
            'user_id' => $this->user->id,
            'service_account_id' => $this->serviceAccount->id
        ]);
        $sa = ServiceAccount::factory()->create();

        $response = $this->actingAs($this->user)->putJson(route('numbers.update', $number), [
            'service_account_id' => $sa->id,
        ]);

        $response->assertUnprocessable();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->has('errors.service_account_id',1)
        );

        $number->refresh();
        $this->assertEquals($this->serviceAccount->id, $number->service_account_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $number = Number::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson(route('numbers.destroy', $number));

        $response->assertNoContent();

        $this->assertDeleted($number);
    }

    /**
     * @test
     */
    public function destroy_respects_auth_policy()
    {
        $number = Number::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('numbers.show', $number));

        $response->assertForbidden();
    }
}
