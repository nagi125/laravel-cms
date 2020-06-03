<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="robots" content="noindex,nofollow" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | {{ config('app.name', 'Laravel')}}</title>
  <link href="{{ asset('/css/app_admin.css') }}" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>

<body class="app">
<div>
  @include('admin._components.sidebar')
  <div class="page-container">
    @include('admin._components.nav')
    <main id="app" class='main-content bgc-grey-100'>
      <div id='mainContent'>
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </main>
  </div>
</div>
<script src="{{ asset('/js/app_admin.js') }}"></script>
</body>
</html>