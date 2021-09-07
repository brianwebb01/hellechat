<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use App\Models\Voicemail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\VoicemailController
 */
class VoicemailControllerTest extends TestCase
{
    use RefreshDatabase;

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
        Voicemail::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);
        Voicemail::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson(route('voicemail.index'));

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
    public function show_behaves_as_expected()
    {
        $voicemail = Voicemail::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->getJson(route('voicemail.show', $voicemail));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $voicemail = Voicemail::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('voicemail.show', $voicemail));

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $voicemail = Voicemail::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('voicemail.destroy', $voicemail));

        $response->assertNoContent();

        $this->assertDeleted($voicemail);
    }

    /**
     * @test
     */
    public function destroy_respects_auth_policy()
    {
        $voicemail = Voicemail::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('voicemail.show', $voicemail));

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }
}
