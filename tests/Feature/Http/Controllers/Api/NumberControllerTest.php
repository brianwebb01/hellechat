<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Number;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\NumberController
 */
class NumberControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $response = $this->get(route('number.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
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
        $user_id = $this->faker->numberBetween(-100000, 100000);
        $service_account_id = $this->faker->numberBetween(-100000, 100000);
        $phone_number = $this->faker->phoneNumber;
        $friendly_label = $this->faker->word;

        $response = $this->post(route('number.store'), [
            'user_id' => $user_id,
            'service_account_id' => $service_account_id,
            'phone_number' => $phone_number,
            'friendly_label' => $friendly_label,
        ]);

        $numbers = Number::query()
            ->where('user_id', $user_id)
            ->where('service_account_id', $service_account_id)
            ->where('phone_number', $phone_number)
            ->where('friendly_label', $friendly_label)
            ->get();
        $this->assertCount(1, $numbers);
        $number = $numbers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $number = Number::factory()->create();

        $response = $this->get(route('number.show', $number));

        $response->assertOk();
        $response->assertJsonStructure([]);
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
        $number = Number::factory()->create();
        $user_id = $this->faker->numberBetween(-100000, 100000);
        $service_account_id = $this->faker->numberBetween(-100000, 100000);
        $phone_number = $this->faker->phoneNumber;
        $friendly_label = $this->faker->word;

        $response = $this->put(route('number.update', $number), [
            'user_id' => $user_id,
            'service_account_id' => $service_account_id,
            'phone_number' => $phone_number,
            'friendly_label' => $friendly_label,
        ]);

        $number->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user_id, $number->user_id);
        $this->assertEquals($service_account_id, $number->service_account_id);
        $this->assertEquals($phone_number, $number->phone_number);
        $this->assertEquals($friendly_label, $number->friendly_label);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $number = Number::factory()->create();

        $response = $this->delete(route('number.destroy', $number));

        $response->assertNoContent();

        $this->assertDeleted($number);
    }
}
