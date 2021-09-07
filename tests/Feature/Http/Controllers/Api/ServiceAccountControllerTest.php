<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ServiceAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ServiceAccountController
 */
class ServiceAccountControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $serviceAccounts = ServiceAccount::factory()->count(3)->create();

        $response = $this->get(route('service-account.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\ServiceAccountController::class,
            'store',
            \App\Http\Requests\Api\ServiceAccountStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $user_id = $this->faker->numberBetween(-100000, 100000);
        $name = $this->faker->name;
        $provider = $this->faker->word;
        $api_key = $this->faker->word;
        $api_secret = $this->faker->word;

        $response = $this->post(route('service-account.store'), [
            'user_id' => $user_id,
            'name' => $name,
            'provider' => $provider,
            'api_key' => $api_key,
            'api_secret' => $api_secret,
        ]);

        $serviceAccounts = ServiceAccount::query()
            ->where('user_id', $user_id)
            ->where('name', $name)
            ->where('provider', $provider)
            ->where('api_key', $api_key)
            ->where('api_secret', $api_secret)
            ->get();
        $this->assertCount(1, $serviceAccounts);
        $serviceAccount = $serviceAccounts->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $serviceAccount = ServiceAccount::factory()->create();

        $response = $this->get(route('service-account.show', $serviceAccount));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Api\ServiceAccountController::class,
            'update',
            \App\Http\Requests\Api\ServiceAccountUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $serviceAccount = ServiceAccount::factory()->create();
        $user_id = $this->faker->numberBetween(-100000, 100000);
        $name = $this->faker->name;
        $provider = $this->faker->word;
        $api_key = $this->faker->word;
        $api_secret = $this->faker->word;

        $response = $this->put(route('service-account.update', $serviceAccount), [
            'user_id' => $user_id,
            'name' => $name,
            'provider' => $provider,
            'api_key' => $api_key,
            'api_secret' => $api_secret,
        ]);

        $serviceAccount->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user_id, $serviceAccount->user_id);
        $this->assertEquals($name, $serviceAccount->name);
        $this->assertEquals($provider, $serviceAccount->provider);
        $this->assertEquals($api_key, $serviceAccount->api_key);
        $this->assertEquals($api_secret, $serviceAccount->api_secret);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $serviceAccount = ServiceAccount::factory()->create();

        $response = $this->delete(route('service-account.destroy', $serviceAccount));

        $response->assertNoContent();

        $this->assertDeleted($serviceAccount);
    }
}
