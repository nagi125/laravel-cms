<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StaffPostRequest
 * @package App\Http\Requests\Admin
 */
class UserPostRequest extends FormRequest
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
            'admin.users.store',
            'admin.users.update',
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
            'name'                  => 'required',
            'kana'                  => '',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|confirmed|regex:/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i',
            'password_confirmation' => 'required',
            'role'                  => 'required',
        ];

        // 更新時にユニークがあると登録済のメールアドレスと重複しバリデーションで弾かれてしまうため
        // 更新時にはパスワードの入力が必須にならないため
        if ($this->route()->action['as'] === 'admin.users.update') {
            $rules['email'] = 'required|email';
            $rules['password'] = 'confirmed|regex:/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i';
            $rules['password_confirmation'] = '';
        }

        return $rules;
    }


    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'name.required'      => 'ユーザー名は必須項目です。',
            'email.required'     => 'メールアドレスは必須項目です。',
            'email.email'        => 'このメールアドレスは有効な形式ではありません。',
            'email.unique'       => 'このメールアドレスは既に登録されています。',
            'password.required'  => 'パスワードは必須項目です。',
            'password.regex'     => '半角英数字それぞれを1種類以上含む8-100文字で入力してください。',
            'password.confirmed' => '確認用パスワードと一致していません。',
            'password_confirmation.required' => 'パスワードは必須項目です。',
            'role.required'      => '権限は必ず選択してください。',
        ];

        return $messages;
    }
}
