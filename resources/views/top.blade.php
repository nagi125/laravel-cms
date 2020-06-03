@extends('_layouts.layout')
@section('title', $title ?? '')
@section('description', $description ?? '')
@section('content')
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
      <div class="inner">
        <h3 class="masthead-brand">CMS</h3>
        <nav class="nav nav-masthead justify-content-center">
          <a class="nav-link active" href="{{ route('top') }}">トップ</a>
          <a class="nav-link" href="{{ route('contact') }}">お問い合わせ</a>
        </nav>
      </div>
    </header>

    <main role="main" class="inner cover">
      <h1 class="cover-heading">{{ $title }}</h1>
      <p class="lead">ここには文章が入ります。</p>
    </main>

    <footer class="mastfoot mt-auto">
    </footer>
  </div>
@endsection
