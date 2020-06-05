@extends('_layouts.layout')
@section('title', $title ?? '')
@section('description', $description ?? '')
@section('content')
  <div>
    <form>
      <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">お名前</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="name" placeholder="苗字 名前">
        </div>
      </div>
      <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">メール</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="email" value="email@example.com">
        </div>
      </div>
      <div class="form-group row">
        <label for="content" class="col-sm-2 col-form-label">内容</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="content" id="content" cols="30" rows="5" placeholder="お問い合わせ内容"></textarea>
        </div>
      </div>
      <div class="text-right">
        <button class="btn btn-secondary px-4">送信</button>
      </div>
    </form>
  </div>
@endsection
