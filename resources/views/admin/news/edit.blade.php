@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  {{ Form::open(['route' => ['admin.news.update', 'news' => $news->id], 'method' => 'put', 'files' => true, 'novalidate' => 'novalidate']) }}
    @csrf
    @method('PUT')
  @include('admin.news._form')
  {{ Form::close() }}
  <div class="my-3 pt-3 border-top">
    <a class="btn btn-outline-secondary" href="{{ route('admin.news.index') }}">一覧に戻る</a>
    <form name="delete" method="POST" action="{{ route('admin.news.destroy', ['news' => $news]) }}" class="d-inline">
      @csrf
      @method('DELETE')
      <input type="button" class="btn btn-outline-danger" role="button" data-bs-toggle="modal" data-bs-target="#deleteModal" value="削除">
      @include('admin._partials.confirm_delete_modal')
    </form>
  </div>
@endsection
