<?php

namespace Database\Seeders;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $ed = User::getEventDispatcher();
        $ed->forget('eloquent.created: '. User::class);

        $this->call([
            UserSeeder::class,
            ContactSeeder::class,
            MessageSeeder::class,
            NumberSeeder::class,
            ServiceAccountSeeder::class,
            VoicemailSeeder::class,
            ThreadSeeder::class
        ]);

        User::observe(UserObserver::class);
    }
}
