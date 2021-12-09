<?php

namespace Tests\Feature\Http\Controllers\Services\Twilio;

use App\Jobs\ProcessTwilioVoicemailJob;
use App\Models\Contact;
use App\Models\Number;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Services\Twilio\VoicemailController
 */
class VoicemailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $number;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->number = Number::factory()->create(['user_id' => $this->user->id]);
    }

    /**
     * @test
     */
    public function connect_behaves_as_expected()
    {
        $response = $this->post(route('webhooks.twilio.voice', [
            'numberHashId' => $this->number->getHashId(),
            'userHashId' => $this->user->getHashId()
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $xml = \simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertEquals('Response', $xml->getName());
        $this->assertObjectHasAttribute('Dial', $xml);
        $this->assertEquals(10, (int)$xml->Dial['timeout']);
        $this->assertEquals('us', $xml->Dial['ringTone']);
        $this->assertEquals(
            $this->number->sip_registration_url,
            (string)$xml->Dial->Sip
        );
        $this->assertObjectHasAttribute('Sip', $xml->Dial);
        $this->assertEquals(route('webhooks.twilio.voice.greeting', [
            'userHashId' => $this->user->getHashId()
        ]), $xml->Dial['action']);
        $this->assertEquals('Dial', $xml->children()[0]->getName());
    }


    /**
     * @test
     */
    public function connect_with_dnd_skips_ring_with_no_contact_setting()
    {
        $number = Number::factory()->create([
            'user_id' => $this->user->id,
            'dnd_calls' => true
        ]);

        $response = $this->post(route('webhooks.twilio.voice', [
            'numberHashId' => $number->getHashId(),
            'userHashId' => $this->user->getHashId()
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $xml = \simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertEquals('Response', $xml->getName());
        $this->assertObjectHasAttribute('Redirect', $xml);
        $this->assertEquals(route('webhooks.twilio.voice.greeting', [
            'userHashId' => $this->user->getHashId()
        ]), $xml->Redirect);
    }


    /**
     * @test
     */
    public function connect_with_dnd_rings_with_contact_setting()
    {
        $number = Number::factory()->create([
            'user_id' => $this->user->id,
            'dnd_calls' => true,
            'dnd_allow_contacts' => true
        ]);
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id
        ]);
        $from = collect($contact->phone_numbers)->values()->first();

        $response = $this->post(route('webhooks.twilio.voice', [
            'numberHashId' => $number->getHashId(),
            'userHashId' => $this->user->getHashId()
        ]), ['From' => $from]);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $xml = \simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertEquals('Response', $xml->getName());
        $this->assertObjectHasAttribute('Dial', $xml);
        $this->assertEquals(10, (int)$xml->Dial['timeout']);
        $this->assertEquals('us', $xml->Dial['ringTone']);
        $this->assertEquals(
            $number->sip_registration_url,
            (string)$xml->Dial->Sip
        );
        $this->assertObjectHasAttribute('Sip', $xml->Dial);
        $this->assertEquals(route('webhooks.twilio.voice.greeting', [
            'userHashId' => $this->user->getHashId()
        ]), $xml->Dial['action']);
        $this->assertEquals('Dial', $xml->children()[0]->getName());
    }


    /**
     * @test
     */
    public function greeting_behaves_as_expected()
    {
        $response = $this->post(route('webhooks.twilio.voice.greeting', [
            'userHashId' => $this->user->getHashId()
        ]), [
            'Called' => '+12125551212'
        ]);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $xml = \simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertEquals('Response', $xml->getName());
        $this->assertEquals(
            "The party you have called is unavailable. Please leave a message after the tone.",
            (string)$xml->Say
        );
        $this->assertEquals(1, (int)$xml->Pause[0]['length']);
        $this->assertObjectHasAttribute('Record', $xml);
        $this->assertEquals(true, (boolean)$xml->Record['playBeep']);
        $this->assertEquals(120, (int)$xml->Record['maxLength']);
        $this->assertEquals(
            route('webhooks.twilio.voice.store', [
                'userHashId' => $this->user->getHashId()
            ]),
            (string)$xml->Record['transcribeCallback']
        );
    }


    /**
     * @test
     */
    public function store_behaves_as_expected()
    {
        Queue::fake();

        $number = Number::factory()->create();

        $response = $this->post(route('webhooks.twilio.voice.store', [
            'userHashId' => $this->user->getHashId()
        ]), [
            'To' => $number->phone_number
        ]);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        Queue::assertPushed(ProcessTwilioVoicemailJob::class);
    }
}
