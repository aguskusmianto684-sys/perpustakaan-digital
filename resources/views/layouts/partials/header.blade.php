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

                {{-- NAMA USER --}}
                <span class="me-2 fw-semibold">
                    {{ Auth::user()->username }}
                </span>

                <li class="nav-item dropdown">

                    {{-- ICON PROFILE (INISIAL) --}}
                    <a class="nav-link nav-icon-hover d-flex align-items-center justify-content-center"
                        href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false"
                        style="
              width:40px;
              height:40px;
              border-radius:50%;
              background:#4e73df;
              color:white;
              cursor:pointer;
              transition:0.3s;
             "
                        onmouseover="this.style.background='#2e59d9'" onmouseout="this.style.background='#4e73df'">

                        <span class="fw-bold">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </span>

                    </a>

                    {{-- DROPDOWN --}}
                    <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="drop2"
                        style="min-width:220px;">

                        {{-- HEADER --}}
                        <div class="px-3 py-2 border-bottom">
                            <strong>{{ Auth::user()->username }}</strong><br>
                            <small class="text-muted text-capitalize">
                                {{ Auth::user()->role }}
                            </small>
                        </div>

                        {{-- PROFILE SESUAI ROLE --}}
                        @if (Auth::user()->role == 'anggota')
                            <a href="/anggota/profile" class="dropdown-item">
                                <i class="ti ti-user"></i> Profil Saya
                            </a>
                        @elseif(Auth::user()->role == 'petugas')
                            <a href="/petugas/profile" class="dropdown-item">
                                <i class="ti ti-user"></i> Profil Saya
                            </a>
                        @elseif(Auth::user()->role == 'kepala')
                            <a href="/kepala/profile" class="dropdown-item">
                                <i class="ti ti-user"></i> Profil Saya
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>

                        {{-- LOGOUT --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="ti ti-logout"></i> Logout
                            </button>
                        </form>

                    </div>

                </li>

            </ul>
        </div>
    </nav>
</header>
