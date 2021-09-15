<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ContactController
 */
class ContactControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_displays_view()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('ui.contact.index'));

        $response->assertOk();
        $response->assertViewIs('contact.index');
    }
}
