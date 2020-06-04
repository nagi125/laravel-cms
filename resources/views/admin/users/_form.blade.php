@includeWhen(session('critical_error_message'), 'admin._components.flash_message_error')
<table class="table table-striped border item-va-middle table-form">
  <tbody>
  <tr>
    <th class="bg-dark text-white border-white"><span class="p-5 mr-2 badge badge-danger">必須</span>名前</th>
    <td>
      @includeWhen($errors->get('name'), 'admin._components.validation_error', ['errors' => $errors->get('name')])
      {{ Form::text('name', old('name', $user->name), ['class' => 'form-control', 'required' => 'required']) }}
    </td>
  </tr>
  <tr>
    <th class="bg-dark text-white border-white">フリガナ</th>
    <td>
      @includeWhen($errors->get('kana'), 'admin._components.validation_error', ['errors' => $errors->get('kana')])
      {{ Form::text('kana', old('kana', $user->kana), ['class' => 'form-control']) }}
    </td>
  </tr>
  <tr>
    <th class="bg-dark text-white border-white"><span class="p-5 mr-2 badge badge-danger">必須</span>メールアドレス</th>
    <td>
      @includeWhen($errors->get('email'), 'admin._components.validation_error', ['errors' => $errors->get('email')])
      {{ Form::email('email', old('email', $user->email), ['class' => 'form-control', 'required' => 'required']) }}
    </td>
  </tr>
  <tr>
    <th class="bg-dark text-white border-white"><span class="p-5 mr-2 badge badge-danger">必須</span>パスワード <small>(半角英数字8文字以上)</small></th>
    <td>
      @includeWhen($errors->get('password'), 'admin._components.validation_error', ['errors' => $errors->get('password')])
      {{ Form::password('password', ['id' => 'password', 'class' => 'form-control']) }}

    </td>
  </tr>
  <tr>
    <th class="bg-dark text-white border-white"><span class="p-5 mr-2 badge badge-danger">必須</span>パスワード(確認用)</th>
    <td>
      @includeWhen($errors->get('password_confirmation'), 'admin._components.validation_error', ['errors' => $errors->get('password_confirmation')])
      {{ Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control']) }}
    </td>
  </tr>
  <tr>
    <th class="bg-dark text-white border-white"><span class="p-5 mr-2 badge badge-danger">必須</span>権限</th>
    <td>
      @includeWhen($errors->get('role'), 'admin._components.validation_error', ['errors' => $errors->get('role')])
      @foreach(EnumUser::ROLES as $key => $val)
        <div class="form-check form-check-inline mr-4">
          {{ Form::radio('role', $key, ($key == old('role', $user->role)), ['id' => 'role'.$key, 'required' => 'required']) }}
          {{ Form::label('role'.$key, $val, ['class' => 'm-0 pl-1']) }}
        </div>
      @endforeach
    </td>
  </tr>
  </tbody>
</table>
<div class="text-center">
  {{ Form::submit('保存', ['class' => 'btn btn-primary px-5']) }}
</div>
