<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Number;
use Tests\TestCase;

class NumberTest extends TestCase
{
    /** @test */
    public function returns_positive_notification_intent_in_default()
    {
        $number = Number::factory()->create();
        $this->assertFalse($number->dnd_calls);
        $this->assertFalse($number->dnd_allow_contacts);
        $this->assertTrue($number->shouldRing());
    }

    /** @test */
    public function returns_positive_notification_intent_in_default_allow_contact()
    {
        $number = Number::factory()->create([
            'dnd_allow_contacts' => true,
        ]);
        $this->assertFalse($number->dnd_calls);
        $this->assertTrue($number->dnd_allow_contacts);
        $this->assertTrue($number->shouldRing());
    }

    /** @test  */
    public function returns_positive_notification_intent_with_settings()
    {
        $number = Number::factory()->create([
            'dnd_calls' => true,
            'dnd_allow_contacts' => true,
        ]);
        $this->assertTrue($number->dnd_calls);
        $this->assertTrue($number->dnd_allow_contacts);
        $this->assertTrue($number->shouldRing(new Contact()));
    }

    /** @test */
    public function returns_negative_notification_allowing_contact_but_no_contact()
    {
        $number = Number::factory()->create([
            'dnd_calls' => true,
            'dnd_allow_contacts' => true,
        ]);
        $this->assertTrue($number->dnd_calls);
        $this->assertTrue($number->dnd_allow_contacts);
        $this->assertFalse($number->shouldRing(null));
    }

    /** @test */
    public function returns_negative_notification()
    {
        $number = Number::factory()->create([
            'dnd_calls' => true,
        ]);
        $this->assertTrue($number->dnd_calls);
        $this->assertFalse($number->dnd_allow_contacts);
        $this->assertFalse($number->shouldRing(new Contact()));
    }
}
