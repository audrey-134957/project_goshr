<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->check()){
                return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->input('submit') == 'draft') {
            // les champs pourront être nullable
            $fieldValidation = 'nullable';
        } else {
            //     //autrement, ils seront requis
            $fieldValidation = 'required';
        }

        return [
            'title'                 =>  'required|string|min:6|max:80|unique:projects,title',
            'thumbnail'             =>  'required|image|mimes:jpg,jpeg,png|max:3000',
            'category'              => "$fieldValidation|exists:categories,id",
            'difficulty_level'      => "$fieldValidation|exists:difficulty_levels,id",
            'material.*'            => 'distinct|nullable|string',
            'material.0'            => "$fieldValidation|string|min:3",
            'duration'              => "$fieldValidation|integer|min:1|max:60",
            'unity_of_measurement'  => "$fieldValidation|exists:unities_of_measurement,id",
            'budget'                => "$fieldValidation|integer|min:1|max:1000",
            'content'               => 'required|min:100'
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
