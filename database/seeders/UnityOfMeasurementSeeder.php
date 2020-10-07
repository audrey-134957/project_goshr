<?php

namespace Database\Seeders;

use App\Models\UnityOfMeasurement;
use Illuminate\Database\Seeder;

class UnityOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnityOfMeasurement::create(['name' => 'min.' , 'level' => 0]);
        UnityOfMeasurement::create(['name' => 'h.' , 'level' => 1]);
    }
}
