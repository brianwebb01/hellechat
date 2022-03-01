<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\DestroyMessageThreadJob;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Number;
use App\Models\Thread;
use App\Models\User;
use Database\Seeders\ThreadSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ThreadController
 */
class ThreadControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        //create a 10 message thread that has no contact
        with(new ThreadSeeder)->seedThread($this->user, false, 10, 7);

        //create 5 contacts, each with 10 message thread
        foreach (range(0, 4) as $c) {
            with(new ThreadSeeder)->seedThread($this->user, true, 10);
        }

        $response = $this->actingAs($this->user)->getJson(route('threads.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json->has('data', 6)
                ->has('data.0.id')
                ->has('data.0.unread')
                ->has('data.0.number_id')
                ->has('data.0.number_phone_number')
                ->has('data.0.phone_number')
                ->has('data.0.last_updated_at')
                ->has('data.0.preview')
                ->has('data.0.contact')
                ->has('links')
                ->has('links.first')
                ->has('links.last')
                ->has('links.prev')
                ->has('links.next')
                ->has('meta')
                ->has('meta.current_page')
                ->has('meta.from')
                ->has('meta.last_page')
                ->has('meta.links')
                ->has('meta.path')
                ->has('meta.per_page')
                ->has('meta.to')
                ->has('meta.total')
        );
    }

    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $contactNumber = with(new ThreadSeeder)
            ->seedThread($this->user, true, 3);

        $response = $this->actingAs($this->user)
            ->getJson(route('threads.show', ['phoneNumber' => $contactNumber]));

        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->has('data.0.id')
                ->has('data.0.number_id')
                ->has('data.0.service_account_id')
                ->has('data.0.contact.id')
                ->has('data.0.contact.first_name')
                ->has('data.0.contact.last_name')
                ->has('data.0.contact.company')
                ->has('data.0.contact.phone_numbers')
                ->has('data.0.from')
                ->has('data.0.to')
                ->has('data.0.body')
                ->has('data.0.error_code')
                ->has('data.0.error_message')
                ->has('data.0.direction')
                ->has('data.0.status')
                ->has('data.0.num_media')
                ->has('data.0.media')
                ->has('data.0.external_identity')
                ->has('data.0.read')
                ->has('links')
                ->has('links.first')
                ->has('links.last')
                ->has('links.prev')
                ->has('links.next')
                ->has('meta')
                ->has('meta.current_page')
                ->has('meta.from')
                ->has('meta.last_page')
                ->has('meta.links')
                ->has('meta.path')
                ->has('meta.per_page')
                ->has('meta.to')
                ->has('meta.total');
        });
    }

    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $contactNumber = with(new ThreadSeeder)
            ->seedThread($this->user, true, 2);
        $messages = $this->user->messages()->where('from', $contactNumber)
            ->orWhere('to', $contactNumber)->get();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('threads.destroy', ['phoneNumber' => $contactNumber]));

        $response->assertNoContent();

        $this->assertModelMissing($messages->first());
        $this->assertModelMissing($messages->last());
    }
}
