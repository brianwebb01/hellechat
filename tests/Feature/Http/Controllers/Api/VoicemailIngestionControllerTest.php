<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\IngestProviderVoicemailJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\VoicemailIngestionController
 */
class VoicemailIngestionControllerTest extends TestCase
{
    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        Queue::fake();

        $response = $this->post(route('voicemail-ingestion.store'));

        Queue::assertPushed(IngestProviderVoicemailJob::class);
    }
}
