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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateGotifyUserRecordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($gUserName=null, $gUserPass=null)
    {
        $userName = $gUserName ?? $this->generateUserName();
        $userPass = $gUserPass ?? $this->generateUserPass();

        $adminGotify = Client::createWithBasicAuth(
            config('services.gotify.user'),
            config('services.gotify.pass'),
            config('services.gotify.url')
        );
        $gUser = $adminGotify->createUser($this->user->email, $userPass);

        $userGotify = Client::createWithBasicAuth(
            $this->user->email,
            $userPass,
            config('services.gotify.url')
        );
        $gClient = $userGotify->createClient(config('app.name'));

        $gApplication = $userGotify->createApplication(config('app.name'));

        $userGotify->updateApplicationImage(
            $gApplication['id'],
            Storage::path('img/speech-bubble.png')
        );

        $update = [
            'gotify_user_id' => $gUser['id'],
            'gotify_user_name' => $userName,
            'gotify_user_pass' => $userPass,
            'gotify_client_id' => $gClient['id'],
            'gotify_client_token' => $gClient['token'],
            'gotify_app_id' => $gApplication['id'],
            'gotify_app_token' => $gApplication['token']
        ];

        foreach ($update as $k => $v) {
            $this->user->{$k} = $v;
        }
        $this->user->save();
    }

    public function generateUserPass()
    {
        return bin2hex(openssl_random_pseudo_bytes(5));
    }

    public function generateUserName()
    {
        $parts[] = DB::table('words_adjectives')->orderByRaw("RAND()")->limit(1)->first()->word;
        $parts[] = DB::table('words_animals')->orderByRaw("RAND()")->limit(1)->first()->word;
        $parts[] = rand(1,9);
        $name = \implode('-', $parts);

        if(User::where('gotify_user_name', $name)->first()){
            $name = $this->generateUserName();
        }

        return $name;
    }
}
