<nav class="navbar p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
    <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
      <img src="{{ asset('corona/assets/images/logo-mini.svg') }}" alt="logo" />
    </a>
  </div>
  <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>

    <ul class="navbar-nav w-100">
      <li class="nav-item w-100">
        <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
          <input type="text" class="form-control" placeholder="Search products">
        </form>
      </li>
    </ul>

    <ul class="navbar-nav navbar-nav-right">
      {{-- Jika user sudah login --}}
      @auth
        <li class="nav-item dropdown">
          <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
            <div class="navbar-profile">
              <img class="img-xs rounded-circle" src="{{ asset('corona/assets/images/faces/face15.jpg') }}" alt="">
              <p class="mb-0 d-none d-sm-block navbar-profile-name">
                {{ Auth::user()->name }}
                <span class="badge bg-info text-dark ms-1">
                  {{ ucfirst(Auth::user()->role) }}
                </span>
              </p>
              <i class="mdi mdi-menu-down d-none d-sm-block"></i>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
            <h6 class="p-3 mb-0">Profile</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-dark rounded-circle">
                  <i class="mdi mdi-settings text-success"></i>
                </div>
              </div>
              <div class="preview-item-content">
                <p class="preview-subject mb-1">Settings</p>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            {{-- Tombol logout --}}
            <form action="{{ route('logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-logout text-danger"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject mb-1">Log out</p>
                </div>
              </button>
            </form>
          </div>
        </li>
      @else
        {{-- Jika belum login --}}
        <li class="nav-item">
          <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
        </li>
      @endauth
    </ul>

    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-format-line-spacing"></span>
    </button>
  </div>
</nav>
