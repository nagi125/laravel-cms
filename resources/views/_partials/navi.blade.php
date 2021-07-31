<nav class="nav nav-masthead justify-content-center">
  @foreach(EnumNavi::ITEMS as $key => $item)
    <a class="nav-link @if(Request::path() == $key) active @endif" href="{{ route($item['route']) }}">{{ $item['name'] }}</a>
  @endforeach
</nav>