<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCommentReply extends FormRequest
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
            'edit_comment_reply_content' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'edit_comment_reply_content.required'        => 'Le contenu est requis pour être envoyé.'
        ];
    }
}
