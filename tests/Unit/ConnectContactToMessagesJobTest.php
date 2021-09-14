<?php

namespace Tests\Unit;

use App\Jobs\ConnectContactToMessagesJob;
use App\Models\Contact;
use App\Models\User;
use Database\Seeders\ThreadSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConnectContactToMessagesJobTest extends TestCase
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
    public function add_contact_to_message_works_as_expected()
    {
        $user = User::factory()->create();
        $seeder = new ThreadSeeder();
        $seeder->seedThread($user, false, 10);

        $this->assertEquals(1, $user->messages->pluck('contact_id')->unique()->count());
        $this->assertNull($user->messages->pluck('contact_id')->unique()->first());

        $phone = $user->messages()
            ->where('from', '!=', $user->numbers->first()->phone_number)
            ->first()
            ->from;
        $contact = Contact::factory()->make([
            'user_id' => $user->id,
            'phone_numbers' => ['mobile' => $phone]
        ]);
        $contact->saveQuietly();
        $this->assertEquals(0, $contact->messages()->count());

        $job = new ConnectContactToMessagesJob($contact);
        $job->handle();

        $contact = $contact->refresh();
        $this->assertEquals(10, $contact->messages()->count());
    }
}
