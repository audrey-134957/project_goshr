<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->check()) {
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
        return [
            'category_name' => 'required|min:3|max:150|string'
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            'category_name.required'      => 'Ce champs est requis.',
            'category_name.min'           => 'La catégorie doit comporter au minimum :min caractères.',
            'category_name.max'           => 'La catégorie doit comporter au minimum :max caractères.',
            'category_name.string'        => 'La catégorie doit être un mot ou une courte phrase.',
        ];
    }
}
