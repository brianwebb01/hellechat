<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Voicemail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\VoicemailController
 */
class VoicemailControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $response = $this->get(route('voicemail.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $voicemail = Voicemail::factory()->create();

        $response = $this->get(route('voicemail.show', $voicemail));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $voicemail = Voicemail::factory()->create();

        $response = $this->delete(route('voicemail.destroy', $voicemail));

        $response->assertNoContent();

        $this->assertDeleted($voicemail);
    }
}
