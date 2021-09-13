<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
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
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 3)
            ->has('data.0.id')
            ->has('data.0.user_id')
            ->has('data.0.name')
            ->has('data.0.provider')
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
            ->get();
        $this->assertCount(1, $serviceAccounts);

        $response->assertCreated();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
            ->has('data.id')
            ->has('data.user_id')
            ->has('data.name')
            ->has('data.provider')
        );
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
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->has('errors.provider', 1)
        );
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
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.name')
                ->has('data.provider')
        );
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $serviceAccount = ServiceAccount::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('service-account.show', $serviceAccount));

        $response->assertForbidden();
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
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.name')
                ->has('data.provider')
        );

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
    }
}
