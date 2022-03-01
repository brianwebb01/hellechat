<?php

namespace Tests\Feature\Jobs;

use App\Jobs\DisconnectContactFromVoicemailsJob;
use App\Models\Contact;
use App\Models\User;
use App\Models\Voicemail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DisconnectContactFromVoicemailsJobTest extends TestCase
{
    /**
     * @test
     */
    public function nullify_contact_voicemails_works_as_expected()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create(['user_id' => $user->id]);
        Voicemail::factory()->count(5)->create(['user_id' => $user->id]);
        Voicemail::factory()->count(5)->create([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
        ]);

        $this->assertEquals(10, $user->voicemails()->count());
        $this->assertEquals(5, $contact->voicemails()->count());

        $voicemailIds = $contact->voicemails->pluck('id');

        $job = new DisconnectContactFromVoicemailsJob($contact->id);
        $job->handle();

        $contactIdsAfter = Voicemail::whereIn('id', $voicemailIds)
            ->get()
            ->pluck('contact_id')
            ->unique();

        $this->assertEquals(1, $contactIdsAfter->count());
        $this->assertNull($contactIdsAfter->first());
        $this->assertEquals(10, $user->voicemails()->count());
    }
}
