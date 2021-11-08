<nav class="sidebar">
  <p class="fw-bold px-3 mt-3 mb-0">ダッシュボード</p>
  <ul class="nav flex-column mb-2">
    <li class="nav-item ps-2">
      <a class="nav-link" href="{{ route('admin.top') }}">
        <i class="bi bi-caret-right me-1"></i>ダッシュボード
      </a>
    </li>
  </ul>

  <p class="fw-bold px-3 mt-3 mb-0">コンテンツ管理</p>
  <ul class="nav flex-column mb-2">
    <li class="nav-item ps-2">
      <a class="nav-link" href="{{ route('admin.news.index') }}">
        <i class="bi bi-caret-right me-1"></i>お知らせ管理
      </a>
    </li>
  </ul>

  <p class="fw-bold px-3 mt-3 mb-0">ユーザー管理</p>
  <ul class="nav flex-column mb-2">
    <li class="nav-item ps-2">
      <a class="nav-link" href="{{ route('admin.users.index') }}">
        <i class="bi bi-caret-right me-1"></i>ユーザー管理
      </a>
    </li>
  </ul>
</nav>