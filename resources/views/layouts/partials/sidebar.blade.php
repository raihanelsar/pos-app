<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="index.html"><img src="{{ asset('corona/assets/images/logo.svg') }}" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="{{ asset('corona/assets/images/logo-mini.svg') }}" alt="logo" /></a>
        </div>

        <ul class="nav">
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('dashboard') }}">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>

          <li class="nav-item nav-category">
            <span class="nav-link">Menu</span>
          </li>

            {{-- Admin --}}
            @if(auth()->user()->role_name == 'admin')
            <li class="nav-item menu-items">
                <a class="nav-link" data-toggle="collapse" href="#master-data" aria-expanded="false" aria-controls="master-data">
                <span class="menu-icon"><i class="mdi mdi-laptop"></i></span>
                <span class="menu-title">Master Data</span>
                <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master-data">

                <ul class="nav flex-column sub-menu">
                    <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('admin.products.index') }}">
                        <span class="menu-icon">
                            <i class="mdi mdi-cube-outline"></i>
                        </span>
                        <span class="menu-title">Produk</span>
                    </a>
                    </li>

                    <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">
                        <span class="menu-icon">
                            <i class="mdi mdi-tag-multiple"></i>
                        </span>
                        <span class="menu-title">Kategori</span>
                    </a>
                    </li>

                </ul>
                </div>
            </li>

            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <span class="menu-icon">
                        <i class="mdi mdi-security"></i>
                    </span>
                    <span class="menu-title">Kelola User</span>
                </a>
            </li>
            @endif

            {{-- Kasir --}}
            @if(auth()->user()->role_name == 'kasir')
            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('kasir.products.index') }}">
                    <span class="menu-icon">
                        <i class="mdi mdi-cube-outline"></i>
                    </span>
                    <span class="menu-title">Produk</span>
                </a>
            </li>
            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('kasir.index') }}">
                    <span class="menu-icon">
                        <i class="mdi mdi-cash"></i>
                    </span>
                    <span class="menu-title">Transaksi</span>
                </a>
            </li>
            @endif

            {{-- Pimpinan --}}
            @if(auth()->user()->role_name == 'pimpinan')
            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('pimpinan.products.index') }}">
                    <span class="menu-icon">
                        <i class="mdi mdi-cube-outline"></i>
                    </span>
                    <span class="menu-title">Produk</span>
                </a>
            </li>
            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('pimpinan.pimpinan.laporan') }}">
                    <span class="menu-icon">
                        <i class="mdi mdi-chart-line"></i>
                    </span>
                    <span class="menu-title">Laporan Penjualan</span>
                </a>
            </li>
            @endif


            <li class="nav-item nav-category">
                <span class="nav-link">Configuration</span>
            </li>

            <li class="nav-item menu-items">
                <a class="nav-link" data-toggle="collapse" href="#setting-akun" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-account"></i>
                </span>
                <span class="menu-title">Setting Akun</span>
                <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="setting-akun">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('password.edit') }}">Ganti Password</a></li>
                </ul>
                </div>
            </li>

            <li class="nav-item menu-items">
                <a class="nav-link" href="{{ route('logout') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-power"></i>
                </span>
                <span class="menu-title">Logout</span>
                </a>
            </li>

            </ul>
        </nav>
