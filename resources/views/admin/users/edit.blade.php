@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  {{ Form::open(['route' => ['admin.users.update', 'user' => $user->id], 'method' => 'put', 'novalidate' => 'novalidate']) }}
    @csrf
    @method('put')
  @include('admin.users._form')
  {{ Form::close() }}
  <div class="my-3 pt-3 border-top">
    <a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">一覧に戻る</a>
    <form name="delete" method="post" action="{{ route('admin.users.destroy', ['user' => $user]) }}" class="d-inline">
      @csrf
      @method('delete')
      <input type="button" class="btn btn-outline-danger" role="button" data-bs-toggle="modal" data-bs-target="#deleteModal" value="削除">
      @include('admin._partials.confirm_delete_modal')
    </form>
  </div>
@endsection
