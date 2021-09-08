<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ServiceAccount;
use App\Models\User;
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
        $serviceAccounts = ServiceAccount::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);
        ServiceAccount::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson(route('service-account.index'));

        $response->assertOk();
        $userIds = collect($response->json("data"))->pluck('user_id')->unique();
        $this->assertEquals(3, count($response->json("data")));
        $this->assertCount(1, $userIds);
        $this->assertEquals($this->user->id, $userIds->first());
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
        $name = $this->faker->name;
        $provider = ['twilio', 'telnyx'][rand(0,1)];
        $api_key = $this->faker->word;
        $api_secret = $this->faker->word;

        $response = $this->actingAs($this->user)->postJson(route('service-account.store'), [
            'name' => $name,
            'provider' => $provider,
            'api_key' => $api_key,
            'api_secret' => $api_secret,
        ]);

        $serviceAccounts = $this->user->service_accounts()
            ->where('name', $name)
            ->where('provider', $provider)
            ->where('api_key', $api_key)
            ->where('api_secret', $api_secret)
            ->get();
        $this->assertCount(1, $serviceAccounts);

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function save_validates_unique_provider_per_user()
    {
        $this->user->service_accounts()->delete();
        $existing = ServiceAccount::factory()->create([
            'user_id' => $this->user->id,
            'provider' => 'twilio'
        ]);

        $name = $this->faker->name;
        $provider = 'twilio';
        $api_key = $this->faker->word;
        $api_secret = $this->faker->word;

        $response = $this->actingAs($this->user)->postJson(route('service-account.store'), [
            'name' => $name,
            'provider' => $provider,
            'api_key' => $api_key,
            'api_secret' => $api_secret,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->getJson(route('service-account.show', $serviceAccount));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $serviceAccount = ServiceAccount::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('service-account.show', $serviceAccount));

        $response->assertForbidden();
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
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $this->user->id
        ]);
        $name = $this->faker->name;
        $api_key = $this->faker->word;

        $response = $this->actingAs($this->user)->putJson(route('service-account.update', $serviceAccount), [
            'name' => $name,
            'api_key' => $api_key,
        ]);

        $serviceAccount->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($this->user->id, $serviceAccount->user_id);
        $this->assertEquals($name, $serviceAccount->name);
        $this->assertEquals($api_key, $serviceAccount->api_key);
    }

    /**
     * @test
     */
    public function update_respects_auth_policy()
    {
        $serviceAccount = ServiceAccount::factory()->create();

        $response = $this->actingAs($this->user)->putJson(route('service-account.update', $serviceAccount), [
            'name' => 'foo'
        ]);
        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('service-account.destroy', $serviceAccount));

        $response->assertNoContent();

        $this->assertDeleted($serviceAccount);
    }

    /**
     * @test
     */
    public function destroy_respects_auth_policy()
    {
        $serviceAccount = ServiceAccount::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('service-account.show', $serviceAccount));

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }
}
