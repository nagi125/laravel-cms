<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NewsPostRequest
 * @package App\Http\Requests\Admin
 */
class NewsPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 呼び出せるpathを限定する
        $paths = [
            'admin.news.store',
            'admin.news.update',
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
            'public_date' => 'required|date',
            'title'       => 'required',
            'body'        => '',
            'image'       => '',
            'description' => '',
        ];

        return $rules;
    }


    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'public_date.required' => '公開日は必ず入力してください。',
            'public_date.date'     => '日付形式で入力してください。',
            'title.required'       => '名前は必ず入力してください。',
        ];

        return $messages;
    }
}
