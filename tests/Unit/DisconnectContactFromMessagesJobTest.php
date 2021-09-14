<?php

namespace Tests\Unit;

use App\Jobs\DisconnectContactFromMessagesJob;
use App\Models\Message;
use App\Models\User;
use Database\Seeders\ThreadSeeder;
use Tests\TestCase;

class DisconnectContactFromMessagesJobTest extends TestCase
{
    /**
     * @test
     */
    public function nullify_contact_messages_works_as_expected()
    {
        $user = User::factory()->create();
        $seeder = new ThreadSeeder();
        $seeder->seedThread($user, true, 10);
        $contact = $user->contacts->first();
        $messageIds = $contact->messages->pluck('id');

        $job = new DisconnectContactFromMessagesJob($contact->id);
        $job->handle();

        $contactIdsAfter = Message::whereIn('id', $messageIds)
            ->get()
            ->pluck('contact_id')
            ->unique();

        $this->assertEquals(1, $contactIdsAfter->count());
        $this->assertNull($contactIdsAfter->first());
    }
}
