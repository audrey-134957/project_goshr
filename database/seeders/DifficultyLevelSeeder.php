<?php

namespace Database\Seeders;

use App\Models\DifficultyLevel;
use Illuminate\Database\Seeder;

class DifficultyLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DifficultyLevel::create(
            [
                'name' => 'Facile',
                'slug' => 'facile',
                'en_name' => 'easy',
                'level' => 1
            ]
        );
        DifficultyLevel::create(
            [
                'name' => 'Moyen',
                'slug' => 'moyen',
                'en_name' => 'medium',
                'level' => 2
            ]
        );
        DifficultyLevel::create(
            [
                'name' => 'Difficile',
                'slug' => 'difficile',
                'en_name' => 'hard',
                'level' => 3
            ]
        );
    }
}
