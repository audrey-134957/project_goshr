<?php

namespace App\Traits;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Models\UnityOfMeasurement;
use App\Services\ProjectService;

use App\Traits\MaterialTrait;


trait ProjectTrait
{

    use MaterialTrait;

    public function editProject($project, Request $request, ProjectService $projectService)
    {
        //pour une question de sécurité, je recherche l'utilisateur connecté
        $user = auth()->user();

        $content = $request->input('content');

        $project_identifier = $project->id_number;

        // je retrouve la catégorie en correspondance avec celle sélectionnée dans le formulaire
        $cat = Category::where('id', $request->category)->firstOrFail('id');

        $difficultyLevel =DifficultyLevel::where('id', $request->difficulty_level)->firstOrFail('id');

        //je retrouve l'unité de mesure en correspondance avec celle sélectionnée dans le formulaire
        $unity = UnityOfMeasurement::where('id', $request->unity_of_measurement)->firstOrFail('id');

        //j'indique chaque donnée de champs pour les colonnes concernées
        $project_datas = [
            'category_id' => $cat->id, //je donne l'id de la catégorie
            'difficulty_level_id' => $difficultyLevel->id,
            'title' => $request->title,
            'duration' => $request->duration,
            'unity_of_measurement_id' => $unity->id, //je donne l'id de l'unité de mesure
            'budget' => $request->budget,
            'content' => $projectService->transformBase64ToUrl($user, $content, $project_identifier)
        ];

        //si le champs 'thumbnail' contain un fichier
        if ($request->hasFile('thumbnail')) {
            // je stocke ce fichier dans une variable
            $thumbnail = $request->file('thumbnail');

            $projectService->uploadThumbnailToUserProjectFolder($project, $user, $thumbnail);
        }

        //j'édite le projet
        $project->update($project_datas);

        $this->editOrCreateMaterial($project, $request);

        // $projectService->deleteUnusedImageBySummernoteOnFolder($content, $project_id_number);

        return $project;
    }


    public function giveTheProjectDifficultyLevel($project)
    {

        if ($project->difficulty_level->level === 2) {
            $difficultyClassName = 'medium';
        } elseif ($project->difficulty_level->level === 3) {
            $difficultyClassName = 'hard';
        } else {
            $difficultyClassName = 'easy';
        }

        return $difficultyClassName;
    }


}
