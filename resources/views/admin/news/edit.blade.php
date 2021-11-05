@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  <h4 class="c-grey-900 mT-10 mB-30">{{ $title }}</h4>
  {{ Form::open(['route' => ['admin.news.update', 'news' => $news->id], 'method' => 'put', 'files' => true]) }}
    @csrf
    @method('PUT')
  @include('admin.news._form')
  {{ Form::close() }}
  <div class="my-3 pt-3 border-top">
    <input type="button" class="btn btn-outline-secondary" onClick="location.href='{{ route('admin.news.index') }}'" value="一覧に戻る"/>
    <form name="delete" method="POST" action="{{ route('admin.news.destroy', ['news' => $news]) }}" class="d-inline">
      @csrf
      @method('DELETE')
      <input type="button" class="btn btn-outline-danger" role="button" data-toggle="modal" data-target="#deleteModal" value="削除">
      @include('admin._components.confirm_delete_modal')
    </form>
  </div>
@endsection
