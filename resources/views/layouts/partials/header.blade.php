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
