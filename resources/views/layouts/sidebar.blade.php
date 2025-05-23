<div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            @if(Auth::user()->profile_image)
                <img class="profile-user-img img-fluid img-circle" src="{{ Auth::user()->profile_image }}" alt="User profile picture">
            @else
                <img class="profile-user-img img-fluid img-circle" src="{{ asset('base.jpg') }}" alt="User profile picture">
            @endif
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->nama }}</a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">Profile</li>
            <li class="nav-item">
                <a href="{{ url('/profile/' . Auth::user()->user_id) }}"
                    class="nav-link {{ $active_menu == 'profile' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-circle"></i>
                    <p>Profil</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link  {{ $active_menu == 'dashboard' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            @if(in_array(Auth::user()->level->level_kode, ['MNG', 'ADM']))
            <li class="nav-header">Data Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ $active_menu == 'level' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Level User</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ $active_menu == 'user' ? 'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data User</p>
                </a>
            </li>
            @endif
            @if(in_array(Auth::user()->level->level_kode, ['MNG', 'ADM', 'STF']))
            <li class="nav-header">Data Supplier</li>
            <li class="nav-item">
                <a href="{{ url('/supplier') }}" class="nav-link {{ $active_menu == 'supplier' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-list-alt"></i>
                    <p>Data Supplier</p>
                </a>
            </li>
            <li class="nav-header">Data Barang</li>
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link {{ $active_menu == 'kategori' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-bookmark"></i>
                    <p>Kategori Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/barang') }}" class="nav-link {{ $active_menu == 'barang' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Data Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/stok') }}" class="nav-link {{ $active_menu == 'stok' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>Stok Barang</p>
                </a>
            </li>
            <li class="nav-header">Transaksi Penjualan</li>
            <li class="nav-item">
                <a href="{{ url('/penjualan') }}" class="nav-link {{ $active_menu == 'penjualan' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Transaksi Penjualan</p>
                </a>
            </li>
            @endif
            <li class="nav-header">Log Out</li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt" style="color:red;"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
<!-- /.sidebar-menu -->
