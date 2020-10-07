<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create(['name' => 'Brouillon', 'status_level' => 1]);
        Status::create(['name' => 'Publié', 'status_level' => 2]);
    }
}
