<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;


class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 1; $i < 10; $i++) {


            //je récupère un projet au hasard
            $project = Project::where('status_id', 2)->get()->random();
            //je récupère  un utilisateur au hasard dans la collection de tous les utilisateurs sauf l'auteur et les admins
            $allUsersExceptAuthor = User::where('id', '!=', $project->user_id)->where('role_id', NULL)->get();


            //je récupère tous les utilisateurs sauf les admins
            $allUsers = User::where('role_id', NULL)->get();
            // //je récupère un utilisateur au hasard
            // $user =  $allUsers->get()->random();
            //ce faker sert pour ajouter des commentaires parents
            Comment::factory()->create([
                'user_id' => $allUsersExceptAuthor->random()->id,
                'commentable_id' => $project->id,
                'commentable_type' => Project::class
            ]);


            // //ce faker sert pour ajouter des commentaires enfants
            Comment::factory()->create([
                'user_id' => $allUsers->random()->id,
                'commentable_id' => Comment::all()->random()->id,
                'commentable_type' => Comment::class
            ]);
        }
    }
}
