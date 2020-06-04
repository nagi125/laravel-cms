@extends('admin._layouts.layout')
@section('title', $title)
@section('content')
  <h4 class="c-grey-900 mT-10 mB-30">{{ $title }}</h4>
  {{ Form::open(['route' => ['admin.news.store'], 'method' => 'post', 'files' => true]) }}
  @csrf
  @method('post')
  @include('admin.news._form')
  <div class="my-3 pt-3 border-top">
    <input type="button" class="btn btn-outline-secondary" onClick="location.href='{{ route('admin.news.index') }}'" value="一覧に戻る"/>
  </div>
  {{ Form::close() }}
@endsection
