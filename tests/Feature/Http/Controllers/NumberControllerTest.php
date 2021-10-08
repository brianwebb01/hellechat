<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\NumberController
 */
class NumberControllerTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('ui.numbers.index'));

        $response->assertOk();
        $response->assertViewIs('number-manager.base');
    }
}
