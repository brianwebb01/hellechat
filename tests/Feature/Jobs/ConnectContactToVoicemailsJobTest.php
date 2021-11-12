<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ConnectContactToVoicemailsJob;
use App\Models\Contact;
use App\Models\User;
use App\Models\Voicemail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConnectContactToVoicemailsJobTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * @test
     */
    public function add_contact_to_voicemail_works_as_expected()
    {
        $user = User::factory()->create();
        Voicemail::factory()->count(3)->create([
            'user_id' => $user->id,
            'contact_id' => null
        ]);

        $phone = $user->voicemails->first()->from;
        $contact = Contact::factory()->make([
            'user_id' => $user->id,
            'phone_numbers' => ['mobile' => $phone]
        ]);
        $contact->saveQuietly();
        $this->assertEquals(0, $contact->voicemails()->count());

        $job = new ConnectContactToVoicemailsJob($contact);
        $job->handle();

        $contact = $contact->refresh();
        $this->assertEquals(1, $contact->voicemails()->count());
        $this->assertEquals(2, $user->voicemails()->whereNull('contact_id')->count());
    }
}
