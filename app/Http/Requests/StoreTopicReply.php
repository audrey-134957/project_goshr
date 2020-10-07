<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicReply extends FormRequest
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
            'topic_reply_content' => 'required'
        ];
    }

    public function messages()
    {

        return [
            'topic_reply_content.required'    => 'Ce champs ne peut pas être laissé vide.'
        ];
    }
}
