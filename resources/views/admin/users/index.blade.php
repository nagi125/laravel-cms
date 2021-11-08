@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  @includeWhen(session('flash_message'), 'admin._partials.flash_message_success')
  {{ Form::open(['route' => ['admin.users.index'], 'class' => 'pb-3 border-bottom', 'method' => 'GET']) }}
  <div class="card">
    <div class="card-header bg-dark text-white">
      検索条件
    </div>
    <div class="card-body bg-light">
      <div class="mb-3">
        {{ Form::label('keyword', '名前', ['class' => 'form-label']) }}
        {{ Form::text('keyword', $params['keyword'] ?? '', ['class' => 'form-control bg-white']) }}
      </div>
      <div class="text-center">
        {{ Form::submit('検索', ['class' => 'btn btn-primary px-5']) }}
      </div>
    </div>
  </div>
  {{ Form::close() }}

  <div id='search_result' class="operation mt-3 py-3">
    <a class="btn btn-outline-primary" href="{{ route('admin.users.create') }}">新規作成</a>
  </div>
  <table class="table table-striped table-bordered table-search-result">
    <thead class="bg-dark text-white">
    <tr>
      <th scope="col" class="id">ID</th>
      <th scope="col" class="name">名前</th>
      <th scope="col" class="name">カナ</th>
      <th scope="col">メールアドレス</th>
      <th scope="col" class="operation">操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->kana }}</td>
        <td>{{ $user->email }}</td>
        <td class="operation">
          <a class="btn btn-outline-success" href="{{ route('admin.users.edit', ['user' => $user->id]) }}">編集</a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="d-flex justify-content-center py-4">
    {{ $users->appends($params)->fragment('search_result')->links() }}
  </div>
@endsection
