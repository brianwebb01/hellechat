<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConnectContactToMessagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contact;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $numbers = collect(
            $this->contact->phone_numbers
        )->values();

        Message::query()
            ->where(fn($query) =>
                $query->whereIn('from', $numbers)
                    ->orWhereIn('to', $numbers)
            )->update([
                'contact_id' => $this->contact->id
            ]);
    }
}
