<div class="header navbar">
  <div class="header-container bg-dark">
    <ul class="nav-left">
      <li>
        <a id='sidebar-toggle' class="sidebar-toggle" href="">
          <i class="c-white ti-menu"></i>
        </a>
      </li>
    </ul>
    <ul class="nav-right pR-10">
      <li class="notifications">
        <a class="no-after text-white">
          <i class="c-white ti-user pR-10"></i>
          <span class="fsz-sm fw-600">{{ Auth::guard('admin')->user()->name }}</span>
        </a>
      </li>
      <li class="notifications">
        <a href="{{ route('logout') }}" class="no-after text-white"
           onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
          <i class="c-white ti-lock pR-10"></i>
          <span class="fsz-sm fw-600">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </div>
</div>