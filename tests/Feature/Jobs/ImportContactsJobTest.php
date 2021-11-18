<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ImportContactsJob;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class ImportContactsJobTest extends TestCase
{
    /** @test */
    public function reads_given_file_path_as_expected()
    {
        $filename = 'path/to/file.vcf';
        $user = User::factory()->create();

        Storage::shouldReceive('get')->once()
            ->with($filename)
            ->andReturn('foo');

        Storage::shouldReceive('delete')->once()
            ->with($filename);

        $mJob = Mockery::mock(ImportContactsJob::class, [$user, $filename])
            ->makePartial();
        $mJob->shouldReceive('createContactsFromVCardExport')
            ->once()
            ->with('foo');

        $mJob->handle();
    }

    /** @test */
    public function saves_vcf_contacts_as_expected()
    {
        $filename = 'tests/assets/test-vcf.vcf';
        $content = \file_get_contents($filename);
        $user = User::factory()->create();

        $job = new ImportContactsJob($user, $filename);
        $job->batchSize = 3;
        $job->createContactsFromVCardExport($content);

        $this->assertEquals(5, $user->contacts()->count());


        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'first_name' => 'Thies-Tillman',
            'last_name' => 'Jacobsen',
            'company' => 'Acme Corp'
        ]);
        $this->assertEquals(
            '+15025557890',
            $user->contacts()
                ->where('first_name', 'Thies-Tillman')
                ->first()
                ->phone_numbers['mobile']
        );


        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'first_name' => 'Lenn',
            'last_name' => 'Biernoth',
            'company' => null,
        ]);
        $lenn = $user->contacts()
            ->where('first_name', 'Lenn')
            ->first();
        $this->assertEquals(
            '+15025556789',
            $lenn->phone_numbers['home']
        );
        $this->assertEquals(
            '+15025555678',
            $lenn->phone_numbers['work']
        );


        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'first_name' => 'Ludwig-Götz',
            'last_name' => 'Graßl',
            'company' => null,
        ]);
        $this->assertEquals(
            '+15025554567',
            $user->contacts()
                ->where('first_name', 'Ludwig-Götz')
                ->first()
                ->phone_numbers['work']
        );


        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'first_name' => 'Marita',
            'last_name' => 'Kreutzer',
            'company' => null,
        ]);
        $this->assertEquals(
            '+15025553456',
            $user->contacts()
                ->where('first_name', 'Marita')
                ->first()
                ->phone_numbers['main']
        );


        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'first_name' => 'Kathi',
            'last_name' => 'Hoelzl',
            'company' => null,
        ]);
        $this->assertEquals(
            '+15025552345',
            $user->contacts()
                ->where('first_name', 'Kathi')
                ->first()
                ->phone_numbers['other']
        );
    }
}
