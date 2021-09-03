<?php

namespace Database\Seeders;

use App\Models\Voicemail;
use Illuminate\Database\Seeder;

class VoicemailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voicemail::factory()->count(5)->create();
    }
}
