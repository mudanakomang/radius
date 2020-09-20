<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('dist/img/world.svg') }}" alt="Logo" class="brand-image img-circle "
             style="opacity: .8">
        <span class="brand-text font-weight-light"><br></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                <a href="#" onclick="event.preventDefault();" id="logout-btn" class="d-block"><i class="nav-icon fas fa-power-off"></i> Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item  {{ (request()->is('/')) ? 'menu-open':'' }}">
                    <a href="{{ url('/') }}" class="nav-link  {{ (request()->is('/')) ? 'active':'' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ (request()->segment(1)=='nas') ? 'menu-open':'' }}">
                    <a href="{{ url('/nas') }}" class="nav-link {{ (request()->segment(1)=='nas') ? 'active':'' }}">
                        <i class="nav-icon fas fa-server"></i>
                        <p>
                            NAS
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ (request()->segment(1)=='userprofile') ? 'menu-open':'' }}">
                    <a href="{{ url('/userprofile') }}" class="nav-link  {{ (request()->segment(1)=='userprofile') ? 'active':'' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item  {{ (request()->segment(1)=='user') ? 'menu-open':'' }}">
                    <a href="{{ url('/user') }}" class="nav-link   {{ (request()->segment(1)=='user') ? 'active':'' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
@push('script')
    <script>
        $('#logout-btn').on('click',function () {
            Swal.fire({
                title: 'Apa anda yakin?',
                text: "Anda akan keluar dari aplikasi ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout'
            }).then((result) => {
                if (result.value===true) {
                Swal.fire(
                    'Berhasil',
                    'Anda akan diarahkan ke halaman Login',
                    'success'

                )
                $('#logout-form').submit()
            }
        })
        })

        function setSidebarState(){
            if(!sessionStorage.getItem("sidebar")) {
                sessionStorage.setItem("sidebar", "true");
            } else {
                if(sessionStorage.getItem("sidebar") === "true") {
                    // toggle was on, turning it off
                    $('body').addClass('sidebar-collapsed')
                    sessionStorage.setItem("sidebar", "false");
                }
                else if(sessionStorage.getItem("sidebar") === "false") {
                    // toggle was off, turning it on
                    $('body').removeClass('sidebar-collapsed')
                    sessionStorage.setItem("sidebar", "true")
                }
            }
        }

        $(document).ready(function () {
          if(sessionStorage.getItem('sidebar')==='true'){
              $('body').addClass('sidebar-collapsed sidebar-collapse')
          }else{
              $('body').removeClass('sidebar-collapsed sidebar-collapse')
          }
        })
        $('#menu-toggle').on('click',function () {
            setSidebarState()
        })
    </script>
    @endpush