<?php

namespace Tests\Unit;

use App\Jobs\ProcessInboundTwilioMessageJob;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Number;
use App\Models\ServiceAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessInboundTwilioMessageJobTest extends TestCase
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
    public function job_saves_message_as_expected()
    {
        $user = User::factory()->create();
        $serviceAccount = ServiceAccount::factory()->create([
            'user_id' => $user->id,
        ]);
        $number = Number::factory()->create([
            'user_id' => $user->id,
            'service_account_id' => $serviceAccount->id,
        ]);
        $contact = Contact::factory()->create([
            'user_id' => $user->id,
            'phone_numbers' => ['mobile' => $this->faker->e164PhoneNumber()],
        ]);
        $data = [
            'From' => $contact->phone_numbers['mobile'],
            'To' => $number->phone_number,
            'Body' => 'Foobar',
            'SmsStatus' => 'received',
            'NumMedia' => 2,
            'MediaUrl0' => $this->faker->imageUrl(),
            'MediaUrl1' => $this->faker->imageUrl(),
            'MessageSid' => 'abc123',
        ];

        $job = new ProcessInboundTwilioMessageJob($data);
        $job->handle();

        $message = $user->messages()
            ->where('from', $contact->phone_numbers['mobile'])
            ->where('to', $number->phone_number)
            ->where('Body', 'Foobar')
            ->first();

        $this->assertNotNull($message);
        $this->assertEquals($number->id, $message->number_id);
        $this->assertEquals($serviceAccount->id, $message->service_account_id);
        $this->assertEquals($contact->id, $message->contact_id);
        $this->assertEquals($contact->phone_numbers['mobile'], $message->from);
        $this->assertEquals($number->phone_number, $message->to);
        $this->assertEquals($data['Body'], $message->body);
        $this->assertEquals(Message::DIRECTION_IN, $message->direction);
        $this->assertEquals($data['SmsStatus'], $message->status);
        $this->assertEquals($data['NumMedia'], $message->num_media);
        $this->assertEquals(2, count($message->media));
        $this->assertTrue(\in_array($data['MediaUrl0']. '&Content-Type=text/html; charset=UTF-8', $message->media));
        $this->assertTrue(\in_array($data['MediaUrl1']. '&Content-Type=text/html; charset=UTF-8', $message->media));
        $this->assertEquals($data['MessageSid'], $message->external_identity);
    }
}
