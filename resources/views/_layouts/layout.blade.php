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

@yield('content')

<script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>