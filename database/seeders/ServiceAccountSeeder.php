<?php

namespace Database\Seeders;

use App\Models\ServiceAccount;
use Illuminate\Database\Seeder;

class ServiceAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceAccount::factory()->count(5)->create();
    }
}
