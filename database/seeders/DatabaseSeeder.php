<?php

namespace Database\Seeders;

use CategorySeeder;
use DifficultyLevelSeeder;
use Illuminate\Database\Seeder;
use MotiveSeeder;
use RankSeeder;
use RoleSeeder;
use StatusSeeder;
use UnitiesOfMeasurementSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            DifficultyLevelSeeder::class,
            MotiveSeeder::class,
            RankSeeder::class,
            RoleSeeder::class,
            StatusSeeder::class,
            UnitiesOfMeasurementSeeder::class,
            UserSeeder::class,
        ]);
    }
}
