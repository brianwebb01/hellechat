<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\ImportContactsJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ContactImportController
 */
class ContactImportControllerTest extends TestCase
{
    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        Queue::fake();

        $response = $this->post(route('contact-import.store'));

        Queue::assertPushed(ImportContactsJob::class);
    }
}
