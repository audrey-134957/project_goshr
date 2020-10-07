<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class EditProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // je retrouve le projet
        $project = Project::find($this->project);

        if (request()->input('submit') == 'draft') {
            // les champs pourront être nullable
            $fieldValidation = 'nullable';
        } else {
            //     //autrement, ils seront requis
            $fieldValidation = 'required';
        }


        //le titre devra répondre aux critères si dessous, en prenant soin d'ignorer la vérification du titre unique pour ce même projet
        $project_material = $project->materials()->first();
        //si mon projet a des materiels
        if ($project_material !== null) {
            //je récupère le premier id du materiel que je viens stocker en variable
            $first_material = $project->materials()->first();

            // dd($project->materials()->first());
            $first_material_id = $first_material->id;
            $field = 'material.' . $first_material_id;
        } else {
            //s'il n'en a pas, le premier champs matériel sera le champs 0
            $field = 'material.0';
        }


        //si ce projet a une image thumbnail
        if ($project->thumbnail !== null) {
            $thumbnailValidation = 'sometimes|image|mimes:jpg,jpeg,png|max:3000';
        }



        return [
            'title'                             =>  "$fieldValidation|string|min:6|max:80|unique:projects,title,$project->id",
            'thumbnail'                         =>  $thumbnailValidation,
            'category'                          => "$fieldValidation|exists:categories,id",
            'difficulty_level'                  => "$fieldValidation|exists:difficulty_levels,id",
            'material.*'                        => 'distinct|nullable|string',
            $field                              => "$fieldValidation|string|min:3",
            'duration'                          => "$fieldValidation|integer|min:1|max:60",
            'unity_of_measurement'              => "$fieldValidation|exists:unities_of_measurement,id",
            'budget'                            => "$fieldValidation|integer|min:1|max:1000",
            'content'                           => 'required|min:100'
        ];
    }


    public function messages()
    {
        return [
            'title.required'                => 'Le titre doit être inscrit.',
            'title.string'                  => 'Le titre doit être une courte phrase',
            'title.min'                     => 'Le titre doit contenir au minimum :min caractères.',
            'title.max'                     => 'Le titre doit contenir  au maximum :max caractères.',
            'title.unique'                  => 'Il semblerait que ce titre soit déjà pris.',

            'thumbnail.required'            => 'Une image thumbnail doit être présent pour illustrer votre projet',
            'thumbnail.image'               => 'Votre fichier doit être une image',
            'thumbnail.mimes'               => "L'image thumbnail doit être de type .jpg, .jpeg, .png",
            'thumbnail.max'                 => "L'image semble être trop lourde",
            'thumbnail.uploaded'            => "Il y a une erreur lors du téléchargement de l'image.",

            'category.required'             => 'Une catégorie doit être choisie pour votre projet.',

            'difficulty_level.required'     => 'Un niveau de difficulté doit être choisie pour votre projet.',

            'material.0.required'           => 'Au moins un matériel doit être renseigné.',
            'material.0.min'                => "Le matériel doit être composé d'au moins :min caractères.",

            'duration.required'             => 'Le temps estimé du projet doit être renseigné.',
            'duration.integer'              => "Ce champs n'accepte que des chiffres",
            'duration.min'                  => 'La durée ne peut pas être inférieure à :min',
            'duration.max'                  => 'La durée ne peut pas être supérieur à :max',

            'budget.required'               => 'Le budget du projet doit être renseignée.',
            'budget.integer'                => "Ce champs n'accepte que des chiffres",
            'budget.min'                    => 'Le budget ne peut pas être inférieur à :min €',
            'budget.max'                    => 'Le budget peut pas dépasser :max €',

            'content.required'              => 'Le contenu du projet ne peut pas être vide.',
            'content.min'                   => 'Le contenu du projet doit contenir au moins :min caractères.',


        ];
    }
}
