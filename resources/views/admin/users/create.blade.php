@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  {{ Form::open(['route' => ['admin.users.store'], 'method' => 'post', 'novalidate' => 'novalidate']) }}
  @csrf
  @method('post')
  @include('admin.users._form')
  <div class="my-3 pt-3 border-top">
    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">一覧に戻る</a>
  </div>
  {{ Form::close() }}
@endsection
