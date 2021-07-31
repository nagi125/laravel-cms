@extends('_layouts.layout')
@section('title', $title ?? '')
@section('description', $description ?? '')
@section('content')
  <div>
    {{ Form::open(['route' => ['contact.submit'], 'method' => 'post']) }}
    @csrf
    @method('post')
    <div class="form-group row">
      <label for="name" class="text-left col-sm-3 col-form-label"><span class="badge-danger p-2 mr-1">必須</span>お名前</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="name" placeholder="苗字 名前">
      </div>
    </div>
    <div class="form-group row">
      <label for="email" class="text-left col-sm-3 col-form-label"><span class="badge-danger p-2 mr-1">必須</span>メール</label>
      <div class="col-sm-9">
        <input type="email" class="form-control" id="email" value="email@example.com">
      </div>
    </div>
    <div class="form-group row">
      <label for="body" class="text-left col-sm-3 col-form-label"><span class="badge-danger p-2 mr-1">必須</span>内容</label>
      <div class="col-sm-9">
        <textarea class="form-control" name="body" id="body" cols="30" rows="5" placeholder="お問い合わせ内容"></textarea>
      </div>
    </div>
    <div class="text-right">
      {{ Form::submit('送信', ['class' => 'btn btn-secondary px-3']) }}
    </div>
    </form>
    {{ Form::close() }}
  </div>
@endsection
