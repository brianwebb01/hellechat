<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\NumberController
 */
class NumberControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

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

        $response = $this->actingAs($this->user)->getJson(route('number.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 5)
                ->has('data.0.id')
                ->has('data.0.user_id')
                ->has('data.0.service_account_id')
                ->has('data.0.phone_number')
                ->has('data.0.friendly_label')
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
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\NumberController::class,
            'store',
            \App\Http\Requests\Api\NumberStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $phone_number = $this->faker->e164PhoneNumber();
        $friendly_label = $this->faker->word;

        $response = $this->actingAs($this->user)->postJson(route('number.store'), [
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
                ->has('data.service_account_id')
                ->has('data.phone_number')
                ->has('data.friendly_label')
        );
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $number = Number::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson(route('number.show', $number));

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.service_account_id')
                ->has('data.phone_number')
                ->has('data.friendly_label')
        );
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $number = Number::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('number.show', $number));

        $response->assertForbidden();
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\NumberController::class,
            'update',
            \App\Http\Requests\Api\NumberUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $number = Number::factory()->create(['user_id' => $this->user->id]);
        $phone_number = $this->faker->e164PhoneNumber();
        $friendly_label = $this->faker->word;

        $response = $this->actingAs($this->user)->putJson(route('number.update', $number), [
            'service_account_id' => $this->serviceAccount->id,
            'phone_number' => $phone_number,
            'friendly_label' => $friendly_label,
        ]);

        $number->refresh();

        $response->assertOk();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.service_account_id')
                ->has('data.phone_number')
                ->has('data.friendly_label')
        );

        $this->assertEquals($this->user->id, $number->user_id);
        $this->assertEquals($this->serviceAccount->id, $number->service_account_id);
        $this->assertEquals($phone_number, $number->phone_number);
        $this->assertEquals($friendly_label, $number->friendly_label);
    }

    /**
     * @test
     */
    public function update_respects_auth_policy()
    {
        $number = Number::factory()->create();

        $response = $this->actingAs($this->user)->putJson(route('number.update', $number), [
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

        $response = $this->actingAs($this->user)->putJson(route('number.update', $number), [
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

        $response = $this->actingAs($this->user)->deleteJson(route('number.destroy', $number));

        $response->assertNoContent();

        $this->assertDeleted($number);
    }

    /**
     * @test
     */
    public function destroy_respects_auth_policy()
    {
        $number = Number::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('number.show', $number));

        $response->assertForbidden();
    }
}
