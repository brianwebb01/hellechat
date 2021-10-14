<?php

namespace Tests\Unit;

use App\Rules\PhoneNumbersInJsonAreE164;
use Tests\TestCase;

class PhoneNumbersInJsonAreE164Test extends TestCase
{
    /** @test */
    public function valid_phones_should_pas()
    {
        $rule = new PhoneNumbersInJsonAreE164;
        $result = $rule->passes('phone_numbers', json_encode([
            'mobile' => '+15510823010',
            'main' => '+15801719657'
        ]));
        $this->assertTrue($result);
    }

    /** @test */
    public function invalid_phones_should_fail_with_pluralized_message()
    {
        $rule = new PhoneNumbersInJsonAreE164;
        $result = $rule->passes('phone_numbers', json_encode([
            'mobile' => 'foo',
            'main' => 'bar'
        ]));
        $this->assertFalse($result);

        $message = $rule->message();
        $this->assertEquals(
            "The 1st and 2nd phone numbers are not in E.164 format.",
            $message
        );
    }

    /** @test */
    public function invalid_phone_should_fail_with_singularized_message()
    {
        $rule = new PhoneNumbersInJsonAreE164;
        $result = $rule->passes('phone_numbers', json_encode([
            'mobile' => 'foo',
        ]));
        $this->assertFalse($result);

        $message = $rule->message();
        $this->assertEquals(
            "The 1st phone number is not in E.164 format.",
            $message
        );
    }
}
