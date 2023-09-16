@php
    $routeName = Route::currentRouteName();
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block text-capitalize">
                    {{ Auth::user()->name }}
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if ($routeName == 'dashboard') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item @if ($routeName == 'service.index' || $routeName == 'service-provider-info.index') menu-is-opening menu-open @endif ">
                    <a href="#" class="nav-link @if($routeName == 'service.index' || $routeName == 'service.show' || $routeName == 'service-provider-info.index') active @endif">
                      <i class="nav-icon fa-solid fa-hammer"></i>
                        <p>
                          Service
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('service.index') }}" class="nav-link  @if ($routeName == 'service.index' || $routeName == 'service.show') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('service-provider-info.index') }}" class="nav-link  @if ($routeName == 'service-provider-info.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Provider Info</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('token.index') }}" class="nav-link @if ($routeName == 'token.index') active @endif">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Token
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-header">MISCELLANEOUS</li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
