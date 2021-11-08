<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Gotify\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteGotifyUserRecordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $gotify_user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $gotify_user_id)
    {
        $this->gotify_user_id = $gotify_user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $adminGotify = Client::createWithBasicAuth(
            config('services.gotify.user'),
            config('services.gotify.pass'),
            config('services.gotify.url')
        );
        $adminGotify->deleteUser($this->gotify_user_id);
    }
}
