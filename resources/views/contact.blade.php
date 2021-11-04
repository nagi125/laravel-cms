@extends('_layouts.layout')
@section('title', $title ?? '')
@section('description', $description ?? '')
@section('content')
  <div>
    @includeWhen($errors->any(), '_partials.errors', ['errors' => $errors])
    {{ Form::open(['route' => ['contact.submit'], 'method' => 'post']) }}
    @csrf
    @method('post')
    <div class="form-group row mb-3">
      <label for="name" class="text-start col-sm-3 col-form-label"><span class="badge bg-danger p-2 me-2">必須</span>お名前</label>
      <div class="col-sm-9">
        <input type="text" id="name" name="name" class="form-control" placeholder="苗字 名前">
      </div>
    </div>
    <div class="form-group row mb-3">
      <label for="email" class="text-start col-sm-3 col-form-label"><span class="badge bg-danger p-2 me-2">必須</span>メール</label>
      <div class="col-sm-9">
        <input type="email" id="email" name="email" class="form-control" placeholder="email@example.com">
      </div>
    </div>
    <div class="form-group row mb-3">
      <label for="body" class="text-start col-sm-3 col-form-label"><span class="badge bg-danger p-2 me-2">必須</span>内容</label>
      <div class="col-sm-9">
        <textarea class="form-control" name="body" id="body" cols="30" rows="5" placeholder="お問い合わせ内容"></textarea>
      </div>
    </div>
    <div class="text-end">
      {{ Form::submit('送信', ['class' => 'btn btn-secondary px-3']) }}
    </div>
    </form>
    {{ Form::close() }}
  </div>
@endsection
