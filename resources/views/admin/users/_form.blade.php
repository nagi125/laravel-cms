@includeWhen(session('critical_error_message'), 'admin._components.flash_message_error')

<div class="card">
  <div class="card-header bg-dark text-white">
    登録内容
  </div>
  <div class="card-body bg-light">
    <div class="mb-3">
      <span class="p-2 me-1 badge bg-danger">必須</span>
      {{ Form::label('name', '名前', ['class' => 'form-label']) }}
      @includeWhen($errors->get('name'), 'admin._partials.validation_error', ['errors' => $errors->get('name')])
      {{ Form::text('name', old('name', $user->name ?? ''), ['class' => 'form-control bg-white', 'required' => 'required']) }}
    </div>

    <div class="mb-3">
      {{ Form::label('kana', 'フリガナ', ['class' => 'form-label']) }}
      @includeWhen($errors->get('kana'), 'admin._partials.validation_error', ['errors' => $errors->get('kana')])
      {{ Form::text('kana', old('kana', $user->kana ?? ''), ['class' => 'form-control bg-white']) }}
    </div>

    <div class="mb-3">
      <span class="p-2 me-1 badge bg-danger">必須</span>
      {{ Form::label('email', 'メールアドレス', ['class' => 'form-label']) }}
      @includeWhen($errors->get('email'), 'admin._partials.validation_error', ['errors' => $errors->get('email')])
      {{ Form::email('email', old('email', $user->email ?? ''), ['class' => 'form-control bg-white', 'required' => 'required']) }}
    </div>

    <div class="mb-3">
      @if($isCreate)
        <span class="p-2 me-1 badge bg-danger">必須</span>
      @endif
      {{ Form::label('password', "パスワード(半角英数字8文字以上)", ['class' => 'form-label']) }}
      @includeWhen($errors->get('password'), 'admin._partials.validation_error', ['errors' => $errors->get('password')])
      {{ Form::password('password', ['id' => 'password', 'class' => 'form-control bg-white']) }}
    </div>

    <div class="mb-3">
      @if($isCreate)
        <span class="p-2 me-1 badge bg-danger">必須</span>
      @endif
      {{ Form::label('password_confirmation', 'パスワード(確認用)', ['class' => 'form-label']) }}
      @includeWhen($errors->get('password_confirmation'), 'admin._partials.validation_error', ['errors' => $errors->get('password_confirmation')])
      {{ Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control bg-white']) }}
    </div>

    <div class="mb-3">
      <span class="p-2 me-1 badge bg-danger">必須</span>
      {{ Form::label('role', '権限', ['class' => 'form-label']) }}
      @includeWhen($errors->get('role'), 'admin._partials.validation_error', ['errors' => $errors->get('role')])
      <div class="ps-1 pt-1">
        @foreach(EnumUser::ROLES as $k => $v)
          <div class="form-check form-check-inline p-0">
            {{ Form::radio('role', $k, ($k == old('role', $user->role)), ['id' => 'role'.$k, 'required' => 'required']) }}
            {{ Form::label('role'.$k, $v, ['class' => 'm-0 ps-1']) }}
          </div>
        @endforeach
      </div>
    </div>

    <div class="text-center">
      {{ Form::submit('保存', ['class' => 'btn btn-primary px-5']) }}
    </div>
  </div>
</div>
