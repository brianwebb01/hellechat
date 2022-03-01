<?php

namespace Tests\Unit;

use App\Models\Number;
use App\Models\Voicemail;
use Tests\TestCase;

class VoicemailTest extends TestCase
{
    /** @test */
    public function returns_positive_notification_intent_in_default()
    {
        $voicemail = Voicemail::factory()->create();
        $this->assertFalse($voicemail->number->dnd_voicemail);
        $this->assertFalse($voicemail->number->dnd_allow_contacts);
        $this->assertTrue($voicemail->shouldNotify());
    }

    /** @test */
    public function returns_positive_notification_intent_in_default_allow_contact()
    {
        $number = Number::factory()->create([
            'dnd_allow_contacts' => true,
        ]);
        $voicemail = Voicemail::factory()->create([
            'number_id' => $number->id,
        ]);
        $this->assertFalse($voicemail->number->dnd_voicemail);
        $this->assertTrue($voicemail->number->dnd_allow_contacts);
        $this->assertTrue($voicemail->shouldNotify());
    }

    /** @test  */
    public function returns_positive_notification_intent_with_settings()
    {
        $number = Number::factory()->create([
            'dnd_voicemail' => true,
            'dnd_allow_contacts' => true,
        ]);
        $voicemail = Voicemail::factory()->create([
            'number_id' => $number->id,
        ]);
        $this->assertTrue($voicemail->number->dnd_voicemail);
        $this->assertTrue($voicemail->number->dnd_allow_contacts);
        $this->assertTrue($voicemail->shouldNotify());
    }

    /** @test */
    public function returns_negative_notification_allowing_contact_but_no_contact()
    {
        $number = Number::factory()->create([
            'dnd_voicemail' => true,
            'dnd_allow_contacts' => true,
        ]);
        $voicemail = Voicemail::factory()->create([
            'number_id' => $number->id,
            'contact_id' => null,
        ]);
        $this->assertTrue($voicemail->number->dnd_voicemail);
        $this->assertTrue($voicemail->number->dnd_allow_contacts);
        $this->assertNull($voicemail->contact);
        $this->assertFalse($voicemail->shouldNotify());
    }

    /** @test */
    public function returns_negative_notification()
    {
        $number = Number::factory()->create([
            'dnd_voicemail' => true,
        ]);
        $voicemail = Voicemail::factory()->create([
            'number_id' => $number->id,
        ]);
        $this->assertTrue($voicemail->number->dnd_voicemail);
        $this->assertFalse($voicemail->number->dnd_allow_contacts);
        $this->assertNotNull($voicemail->contact);
        $this->assertFalse($voicemail->shouldNotify());
    }
}
