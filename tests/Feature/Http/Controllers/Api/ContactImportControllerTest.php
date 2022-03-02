<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\ImportContactsJob;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
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
        Storage::fake('contact-imports');

        $file = UploadedFile::fake()->create(
            'contacts.vcf', 100, 'text/vcard'
        );
        $filepath = 'contact-imports/'.$file->hashName();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(
            route('contacts-import.store'),
            ['import' => $file]
        );

        $response->assertNoContent();

        Storage::assertExists($filepath);

        Queue::assertPushed(function (ImportContactsJob $job) use ($user, $filepath) {
            return $job->user->id === $user->id &&
                $job->filepath == $filepath;
        });
    }

    /** @test */
    public function store_enforces_filesize_limitation()
    {
        Queue::fake();
        Storage::fake('contact-imports');

        $file = UploadedFile::fake()->create(
            'contacts.vcf',
            600,
            'text/vcard'
        );
        $filepath = 'contact-imports/'.$file->hashName();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(
            route('contacts-import.store'),
            ['import' => $file]
        );

        $response->assertUnprocessable();
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('message')
            ->has('errors')
                ->has('errors.import', 1)
                ->where('errors.import.0', 'The import must not be greater than 512 kilobytes.')
        );

        Queue::assertNotPushed(ImportContactsJob::class);
    }

    /** @test */
    public function validates_file_requirement()
    {
        Queue::fake();
        Storage::fake('contact-imports');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(
            route('contacts-import.store'),
            ['import' => 'text-data']
        );

        $response->assertUnprocessable();
        $response->assertJson(fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->has('errors.import', 2)
                ->where('errors.import.0', 'The import must be a file.')
                ->where('errors.import.1', 'The import must be a file of type: text/vcard.')
        );

        Queue::assertNotPushed(ImportContactsJob::class);
    }
}
