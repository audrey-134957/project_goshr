<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Material;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Project::factory()
            ->times(50)
            ->has(Material::factory()->count(5))
            ->create([
                'status_id' => 2
            ]);


        Project::factory()
            ->times(10)
            ->create([
                'status_id' => 1
            ]);
    }
}
