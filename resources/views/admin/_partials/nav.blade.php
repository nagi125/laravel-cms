<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="row container-fluid pe-0">
    <div class="col-md-auto me-auto nav">
      <div class="navbar-brand nav-item">
        <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
      </div>
    </div>
    <div class="col-md-auto ms-auto">
      <div class="user-info">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navDropdown" aria-controls="navDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-row" id="navDropdown">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white" href="#" id="navDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person me-1"></i>
                {{ Auth::guard('admin')->user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navDropdownMenuLink">
                <li><a class="dropdown-item text-white" href="{{ route('logout') }}">ログアウト</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>
