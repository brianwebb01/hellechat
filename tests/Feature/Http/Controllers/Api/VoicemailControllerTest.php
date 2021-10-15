<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use App\Models\Voicemail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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

        $response = $this->actingAs($this->user)->getJson(route('voicemails.index'));

        $response->assertOk();
        $userIds = collect($response->json("data"))->pluck('user_id')->unique();
        $this->assertEquals(3, count($response->json("data")));
        $this->assertCount(1, $userIds);
        $this->assertEquals($this->user->id, $userIds->first());
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 3)
                ->has('data.0.id')
                ->has('data.0.user_id')
                ->has('data.0.number_id')
                ->has('data.0.contact')
                ->has('data.0.media_url')
                ->has('data.0.length')
                ->has('data.0.from')
                ->has('data.0.created_at')
                ->has('data.0.transcription')
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
    public function show_behaves_as_expected()
    {
        $voicemail = Voicemail::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->getJson(route('voicemails.show', $voicemail));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.id')
                ->has('data.user_id')
                ->has('data.number_id')
                ->has('data.contact')
                ->has('data.from')
                ->has('data.created_at')
                ->has('data.media_url')
                ->has('data.length')
                ->has('data.transcription')
        );
    }

    /**
     * @test
     */
    public function show_respects_auth_policy()
    {
        $voicemail = Voicemail::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('voicemails.show', $voicemail));

        $response->assertForbidden();
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $voicemail = Voicemail::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('voicemails.destroy', $voicemail));

        $response->assertNoContent();

        $this->assertDeleted($voicemail);
    }

    /**
     * @test
     */
    public function destroy_respects_auth_policy()
    {
        $voicemail = Voicemail::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('voicemails.show', $voicemail));

        $response->assertForbidden();
    }
}
