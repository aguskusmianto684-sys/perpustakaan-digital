<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perpustakaan Digital</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/buku.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center text-nowrap logo-img">
                {{-- LOGO --}}
                <img src="{{ asset('assets/images/logos/buku.png') }}"
                    width="75"
                    height="75"
                    alt="logo"
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
            <a class="sidebar-link {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}" href="/anggota/dashboard">
                <i class="ti ti-home"></i>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>

        {{-- BUKU --}}
        <li class="sidebar-item">
            <a class="sidebar-link
            {{ request()->segment(2) == 'buku' || request()->segment(2) == 'pinjam' ? 'active' : '' }}"
            href="/anggota/buku">
                <i class="ti ti-book"></i>
                <span class="hide-menu">Buku</span>
            </a>
        </li>

        {{-- BUKU SAYA --}}
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->segment(2) == 'peminjaman' ? 'active' : '' }}" href="/anggota/peminjaman">
                <i class="ti ti-book"></i>
                <span class="hide-menu">Buku Saya</span>
            </a>
        </li>

        {{-- RIWAYAT --}}
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->segment(2) == 'riwayat' ? 'active' : '' }}" href="/anggota/riwayat">
                <i class="ti ti-history"></i>
                <span class="hide-menu">Riwayat</span>
            </a>
        </li>

    </ul>
</nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->


    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <span class="me-2">
                    {{ Auth::user()->username }}
                </span>
              <li class="nav-item dropdown">

                <a class="nav-link nav-icon-hover d-flex align-items-center justify-content-center"
       href="javascript:void(0)"
       id="drop2"
       data-bs-toggle="dropdown"
       aria-expanded="false"
       style="
        width:40px;
        height:40px;
        border-radius:50%;
        background:#e9ecef;
        cursor:pointer;
        transition:0.3s;
       "
       onmouseover="this.style.background='#d6d6d6'"
       onmouseout="this.style.background='#e9ecef'"
    >

      <i class="ti ti-user"></i>

    </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger mx-3 mt-2 w-100">
                            Logout
                        </button>
                    </form>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->


<div class="container-fluid">
    @yield('content')



        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">AdminMart.com</a> Distributed by <a href="https://themewagon.com">ThemeWagon</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @if(session('success'))
<script>
Swal.fire({
icon: 'success',
title: 'Berhasil',
text: '{{ session('success') }}',
timer: 2000,
showConfirmButton: false
})
</script>
@endif

<script>
$(document).ready(function () {

$('.datatable').DataTable({
pageLength:5,
lengthMenu:[5,10,25,50],
language:{
search:"Cari Data:",
lengthMenu:"Tampilkan _MENU_ data",
info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
paginate:{
previous:"Sebelumnya",
next:"Berikutnya"
}
}
});

});
</script>

@if(session('error'))

<script>

Swal.fire({
icon:'error',
title:'Gagal',
text:'{{ session("error") }}'
})

</script>

@endif

{{-- ALERT SUCCESS --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

<script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>
</body>

</html>

