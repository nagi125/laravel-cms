@includeWhen(session('critical_error_message'), 'admin._partials.flash_message_error')

<div class="card">
  <div class="card-header bg-dark text-white">
    登録内容
  </div>
  <div class="card-body bg-light">
    <div class="mb-3">
      <span class="p-2 me-1 badge bg-danger">必須</span>
      {{ Form::label('public_date', '公開日', ['class' => 'form-label']) }}
      @includeWhen($errors->get('public_date'), 'admin._partials.validation_error', ['errors' => $errors->get('public_date')])
      <date-picker-component :target="'public_date'" :value="{{ json_encode(old('public_date', $news->public_date ? $news->public_date->format('Y-m-d') : '')) }}"></date-picker-component>
    </div>

    <div class="mb-3">
      <span class="p-2 me-1 badge bg-danger">必須</span>
      {{ Form::label('title', 'タイトル', ['class' => 'form-label']) }}
      @includeWhen($errors->get('title'), 'admin._partials.validation_error', ['errors' => $errors->get('title')])
      {{ Form::text('title', old('title', $news->title ?? ''), ['class' => 'form-control bg-white', 'required' => 'required']) }}
    </div>

    <div class="mb-3">
      {{ Form::label('body', '記事内容', ['class' => 'form-label']) }}
      @includeWhen($errors->get('body'), 'admin._partials.validation_error', ['errors' => $errors->get('body')])
      {!! Form::textarea('body', old('body', $news->body ?? ''), ['class' => 'form-control bg-white', 'rows' => '20']) !!}
    </div>

    <div class="mb-3">
      {{ Form::label('image', '写真', ['class' => 'form-label']) }}
      @includeWhen($errors->get('image'), 'admin._partials.validation_error', ['errors' => $errors->get('image')])
      <image-form-component :id="'image'" :name="'image'" image-path="{{ $imageUrl ?? ''}}" ></image-form-component>
      @if($news->image)
        <div class="mt-2 ml-4">
          {{ Form::checkbox('is_delete', true, false, ['id' => 'is_delete', 'class' => 'form-check-input']) }}
          {{ Form::Label('is_delete', '削除', ['class' => 'form-check-label']) }}
        </div>
      @endif
    </div>

    <div class="mb-3">
      {{ Form::label('title', 'Description', ['class' => 'form-label']) }}
      @includeWhen($errors->get('description'), 'admin._partials.validation_error', ['errors' => $errors->get('description')])
      {{ Form::text('description', old('description', $news->description ?? ''), ['class' => 'form-control bg-white', 'required' => 'required']) }}
    </div>

    <div class="text-center">
      {{ Form::submit('保存', ['class' => 'btn btn-primary px-5']) }}
    </div>
  </div>
</div>
