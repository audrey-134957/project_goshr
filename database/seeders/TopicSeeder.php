<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 25; $i++) {

            //je récupère un projet au hasard
            $project = Project::where('status_id', 2)->get()->random();
            //je récupère tous les utilisateurs sauf l'auteur et les admins
            $allUsersExceptAuthor = User::where('id', '!=', $project->user_id)->where('role_id', NULL)->get();


            //je récupère tous les utilisateurs sauf les admins
            $allUsers = User::where('role_id', NULL)->get();

            //ce faker sert pour ajouter des topics parents
            Topic::factory()->create([
                'user_id' => $allUsersExceptAuthor->random()->id,
                'topicable_id' => $project->id,
                'topicable_type' => Project::class,
            ]);

            //ce faker sert pour ajouter des topics enfants
            Topic::factory()->create([
                'user_id' => $allUsers->random()->id,
                'topicable_id' => Topic::all()->random()->id,
                'topicable_type' => Topic::class,
            ]);
        }
    }
}
