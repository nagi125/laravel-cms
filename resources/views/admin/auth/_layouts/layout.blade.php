<!DOCTYPE html>
<html lang="ja">
<head>
  <head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'Laravel')}}</title>

    <link href="{{ asset('/css/app_admin.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
  </head>
</head>
<body class="app">
<div id="app">
  <nav class="nav-top fixed-top flex-column">
    <div class="navbar navbar-dark bg-dark p-0 flex-row flex-nowrap">
      <a class="h6 navbar nav-link text-white m-0 py-3" href="">{{ config('app.name', 'Laravel') }}</a>
    </div>
  </nav>
  <main class="mt-5 py-4">
    @yield('content')
  </main>
</div>
</body>
</html>
