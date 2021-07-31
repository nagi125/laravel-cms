<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description')" />
  <title>@yield('title') @if(Route::currentRouteName() != 'top') | {{ config('app.name', 'Laravel')}}@endif</title>

  <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>

<body class="text-center">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="masthead mb-auto">
    <div class="inner">
      <h3 class="masthead-brand">CMS</h3>
      @include('_partials.navi')
    </div>
  </header>
  <main role="main" class="inner cover">
    <h1 class="cover-heading pb-4">{{ $title }}</h1>
    @yield('content')
  </main>
  <footer class="mastfoot mt-auto">
  </footer>
</div>
<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>