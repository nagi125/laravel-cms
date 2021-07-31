<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ContactPostRequest
 * @package App\Http\Requests\Admin
 */
class ContactPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $paths = [
            'contact.submit',
        ];

        return (in_array($this->route()->action['as'], $paths));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'  => 'required',
            'email' => 'required|email',
            'body'  => 'required',
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'name.required'  => '名前は必須項目です。',
            'email.required' => 'メールは必須項目です。',
            'email.email'    => '有効なメールアドレスの形式ではありません。',
            'body.required'  => '内容は必須項目です。',
        ];

        return $messages;
    }
}
