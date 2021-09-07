<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\IngestProviderMessageJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\MessageIngestionController
 */
class MessageIngestionControllerTest extends TestCase
{
    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        Queue::fake();

        $response = $this->post(route('message-ingestion.store'));

        Queue::assertPushed(IngestProviderMessageJob::class);
    }
}
