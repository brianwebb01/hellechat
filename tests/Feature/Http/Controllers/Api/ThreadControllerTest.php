<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\DestroyMessageThreadJob;
use App\Models\Message;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ThreadController
 */
class ThreadControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $response = $this->get(route('thread.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $message = Message::factory()->create();

        $response = $this->delete(route('thread.destroy', ['phoneNumber' => $message->from]));

        $response->assertNoContent();

        $this->assertDeleted($message);

    }
}
