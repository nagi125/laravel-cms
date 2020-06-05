@extends('_layouts.layout')
@section('title', $title ?? '')
@section('description', $description ?? '')
@section('content')
  <div class="content text-left">
    <dl class="news">
      <dt class="border-left-title h2">News</dt>
      <dd>
        <ul>
          @foreach($news as $post)
            <li>
              <p>
                <span>{{ $post->public_date->format('Y.m.d') }}</span>
                <a href="" class="text_link">{{ $post->title }}</a>
              </p>
            </li>
          @endforeach
        </ul>
      </dd>
    </dl>
  </div>
@endsection
