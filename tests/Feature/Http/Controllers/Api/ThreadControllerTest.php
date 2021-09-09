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
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\Fluent\AssertableJson;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ThreadController
 */
class ThreadControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

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
        foreach (range(0, 4) as $c)
            with(new ThreadSeeder)->seedThread($this->user, true, 10);

        $response = $this->actingAs($this->user)->getJson(route('thread.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data',6)
                ->has('data.0.phoneNumber')
                ->has('data.0.messages')
                ->has('data.0.lastUpdatedAt')
                ->has('data.0.previewBody')
                ->has('data.0.contact')
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
            ->getJson(route('thread.show', ['phoneNumber' => $contactNumber]));

        $response->assertOk();
        $response->assertJson(function(AssertableJson $json){
            $json->has('data')
                ->has('data.phoneNumber')
                ->has('data.messages', 3)
                ->has('data.lastUpdatedAt')
                ->has('data.previewBody')
                ->has('data.contact');
            collect(Schema::getColumnListing('messages'))
                ->map(fn ($c) => "data.messages.0.{$c}")
                ->each(fn ($e) => $json->has($e));
            collect(Schema::getColumnListing('contacts'))
                ->map(fn ($c) => "data.contact.{$c}")
                ->each(fn ($e) => $json->has($e));
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
            ->deleteJson(route('thread.destroy', ['phoneNumber' => $contactNumber]));

        $response->assertNoContent();

        $this->assertDeleted($messages->first());
        $this->assertDeleted($messages->last());
    }
}
