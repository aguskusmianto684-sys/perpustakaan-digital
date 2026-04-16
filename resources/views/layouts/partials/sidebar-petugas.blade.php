<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center text-nowrap logo-img">
                {{-- LOGO --}}
                <img src="{{ asset('assets/images/logos/buku.png') }}" width="75" height="75" alt="logo"
                    class="me-2">

                {{-- TEXT --}}
                <div style="line-height:1.2;">
                    <span style="font-weight:700; font-size:18px; color:#000;">
                        Perpustakaan
                    </span><br>
                    <span style="font-weight:500; font-size:14px; color:#000;">
                        Web
                    </span>
                </div>
            </div>

            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                </li>

                {{-- DASHBOARD --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}"
                        href="/petugas/dashboard">
                        <i class="ti ti-home"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                {{-- BUKU --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->segment(2) == 'buku' ? 'active' : '' }}" href="/petugas/buku">
                        <i class="ti ti-book"></i>
                        <span class="hide-menu">Buku</span>
                    </a>
                </li>

                {{-- ANGGOTA --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->segment(2) == 'anggota' ? 'active' : '' }}"
                        href="/petugas/anggota">
                        <i class="ti ti-users"></i>
                        <span class="hide-menu">Anggota</span>
                    </a>
                </li>

                {{-- PEMINJAMAN --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->segment(2) == 'peminjaman' ? 'active' : '' }}"
                        href="/petugas/peminjaman">
                        <i class="ti ti-book"></i>
                        <span class="hide-menu">Peminjaman Buku</span>
                    </a>
                </li>

                {{-- RIWAYAT --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('petugas/riwayat*') ? 'active' : '' }}"
                        href="{{ route('petugas.riwayat') }}">

                        <i class="ti ti-history"></i>
                        <span class="hide-menu">Riwayat Peminjaman</span>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->

    </div>
    <!-- End Sidebar scroll-->
</aside>
