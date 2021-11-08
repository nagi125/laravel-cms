@extends('admin._layouts.app')
@section('title', $title)
@section('content')
  @includeWhen(session('flash_message'), 'admin._partials.flash_message_success')

  {{ Form::open(['route' => ['admin.news.index'], 'class' => 'pb-3 border-bottom', 'method' => 'GET']) }}
  <div class="card">
    <div class="card-header bg-dark text-white">
      検索条件
    </div>
    <div class="card-body bg-light">
      <div class="mb-3">
        {{ Form::label('keyword', 'タイトル', ['class' => 'form-label']) }}
        {{ Form::text('keyword', $params['keyword'] ?? '', ['class' => 'form-control bg-white']) }}
      </div>
      <div class="text-center">
        {{ Form::submit('検索', ['class' => 'btn btn-primary px-5']) }}
      </div>
    </div>
  </div>
  {{ Form::close() }}

  <div id='search_result' class="operation mt-3 py-3">
    <a class="btn btn-outline-primary" href="{{ route('admin.news.create') }}">新規作成</a>
  </div>
  <table class="table table-striped table-bordered table-search-result">
    <thead class="bg-dark text-white">
    <tr>
      <th scope="col" class="id">ID</th>
      <th scope="col" class="date">公開日</th>
      <th scope="col" class="title">タイトル</th>
      <th scope="col" class="datetime">作成日</th>
      <th scope="col" class="operation">操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($news as $post)
      <tr>
        <td >{{ $post->id }}</td>
        <td >{{ $post->public_date->format('Y年m月d日') }}</td>
        <td >{{ $post->title }}</td>
        <td >{{ $post->created_at }}</td>
        <td class="operation">
          <button type="button" class="btn btn-outline-success" onClick="location.href='{{ route('admin.news.edit', ['news' => $post->id]) }}'">編集</button>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="d-flex justify-content-center py-4">
    {{ $news->appends($params)->fragment('search_result')->links() }}
  </div>
@endsection
