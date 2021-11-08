@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  {{ Form::open(['route' => ['admin.news.store'], 'method' => 'post', 'files' => true, 'novalidate' => 'novalidate']) }}
  @csrf
  @method('post')
  @include('admin.news._form')
  <div class="my-3 pt-3 border-top">
    <input type="button" class="btn btn-outline-secondary" onClick="location.href='{{ route('admin.news.index') }}'" value="一覧に戻る"/>
  </div>
  {{ Form::close() }}
@endsection
