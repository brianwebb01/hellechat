<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\IngestProviderMessageJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\MessageIngestionController
 */
class MessageIngestionControllerTest extends TestCase
{
    /**
     * @test
     */
    public function store_fails_with_invaid_token()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $response = $this->post(route('message-ingestion', ['token' => 'blah']), [
            'foo' => 'bar'
        ]);

        $response->assertForbidden();
        $response->assertJsonStructure([]);
    }

    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        Queue::fake();

        $user = User::factory()->create();
        $token = $user->createToken('foo');
        $plainTextToken = last(explode('|', $token->plainTextToken));

        $response = $this->post(route('message-ingestion', ['token' => $plainTextToken]),[
            'foo' => 'bar'
        ]);

        Queue::assertPushed(IngestProviderMessageJob::class);
        $response->assertOk();
    }
}
