@extends('admin._layouts.layout')
@section('title', $title)
@section('content')
  <h4 class="c-grey-900 mT-10 mB-30">{{ $title }}</h4>
  {{ Form::open(['route' => ['admin.users.store'], 'method' => 'post']) }}
  @csrf
  @method('post')
  @include('admin.users._form')
  <div class="my-3 pt-3 border-top">
    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">一覧に戻る</a>
  </div>
  {{ Form::close() }}
@endsection
