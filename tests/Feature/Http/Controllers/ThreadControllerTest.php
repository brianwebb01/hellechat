<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ThreadController
 */
class ThreadControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function index_displays_view()
    {
        $response = $this->actingAs($this->user)->get(route('ui.thread.index'));

        $response->assertOk();
        $response->assertViewIs('thread.index');
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $response = $this->actingAs($this->user)->get(route('ui.thread.show', ['phoneNumber' => '+12125551212']));

        $response->assertOk();
        $response->assertViewIs('thread.show');
    }
}
