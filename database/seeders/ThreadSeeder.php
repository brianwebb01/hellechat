<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create(['email' => 'user@threads.com']);
        Contact::factory()
            ->count(5)
            ->create(['user_id' => $user->id])
            ->each(function($contact) use($user){
                $contact->messages()->saveMany(
                    Message::factory()->count(10)->make([
                        'user_id' => $user->id
                    ])
                );
            });
    }
}
