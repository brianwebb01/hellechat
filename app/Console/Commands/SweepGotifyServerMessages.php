<?php

namespace App\Console\Commands;

use App\Jobs\DeleteGotifyServerMessageJob;
use App\Models\User;
use App\Services\Gotify\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SweepGotifyServerMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sweep-gotify-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old messages from Gotify server to save space';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::whereNotNull('gotify_client_token')->whereNotNull('gotify_app_id')->chunk(100, function($users) {

            foreach($users as $user) {

                $gotify = Client::createWithTokenAuth(
                    $user->gotify_client_token,
                    config('services.gotify.url')
                );

                $result = $gotify->getApplicationMessages($user->gotify_app_id, 100);
                $this->processMessages($user, $result['messages']);

                while(\array_key_exists('next', $result['paging'])){

                    $result = $gotify->getApplicationMessages($user->gotify_app_id, 100, $result['paging']['since']);
                    $this->processMessages($user, $result['messages']);
                }
            }
        });

        return Command::SUCCESS;
    }


    protected function processMessages(User $user, array $messages) {

        foreach ($messages as $message) {

            if (now()->diffInDays(Carbon::parse($message['date'])) > 7) {

                DeleteGotifyServerMessageJob::dispatch(
                    $user->gotify_client_token,
                    $message['id']
                )->delay(now()->addMinutes(1));
            }
        }
    }
}
