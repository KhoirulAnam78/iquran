<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box mb-3">
        <!-- Dark Logo-->
        {{-- <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                Khoirul Anam
            </span>
            <span class="logo-lg">
                Khoirul Anam
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                Khoirul Anam
            </span>
            <span class="logo-lg">
                Khoirul Anam
            </span>
        </a> --}}
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    {{-- <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="assets/images/users/avatar-1.jpg" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">Anna Adame</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome Anna!</h6>
            <a class="dropdown-item" href="pages-profile.html"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="apps-chat.html"><i
                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Messages</span></a>
            <a class="dropdown-item" href="apps-tasks-kanban.html"><i
                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Taskboard</span></a>
            <a class="dropdown-item" href="pages-faqs.html"><i
                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Help</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="pages-profile.html"><i
                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance :
                    <b>$5971.67</b></span></a>
            <a class="dropdown-item" href="pages-profile-settings.html"><span
                    class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                    class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Settings</span></a>
            <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                    screen</span></a>
            <a class="dropdown-item" href="auth-logout-basic.html"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle"
                    data-key="t-logout">Logout</span></a>
        </div>
    </div> --}}
    <div id="scrollbar">
        <div class="container-fluid">


            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}"><i
                            class="ri-dashboard-2-line"></i>
                        Dashboard </a>
                </li>

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                @if (session()->has('menus'))
                    @foreach (session()->get('menus') as $m)
                        @if (count($m->child) == 0 && !$m->parent_menu_id)
                            @can($m->permission)
                                {{-- <li class="nav-item">
                                <a href="{{ route($m->route) }}" wire:navigate
                                    class="nav-link {{ Route::is($m->route) ? 'active' : '' }}"><i
                                        class="{{ $m->icon }}"></i>
                                    {{ $m->name }} </a>
                            </li> --}}
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ in_array(request()->route()->action['as'], $m->child()->pluck('route')->toArray()) ? 'collapsed active' : '' }}"
                                        href="#sidebar{{ $m->permission }}" data-bs-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="sidebar{{ $m->permission }}">
                                        <i class="{{ $m->icon }}"></i> <span
                                            data-key="t-dashboards">{{ $m->name }}</span>
                                    </a>
                                    <div class="collapse menu-dropdown {{ in_array(request()->route()->action['as'], $m->child()->pluck('route')->toArray()) ? 'show' : '' }}"
                                        id="sidebar{{ $m->permission }}">
                                        <ul class="nav nav-sm flex-column">
                                            @foreach ($m->child as $i)
                                                @can($i->permission)
                                                    <li class="nav-item">
                                                        <a href="{{ route($i->route) }}" wire:navigate
                                                            class="nav-link {{ Route::is($i->route) ? 'active' : '' }}"
                                                            data-key="t-analytics">
                                                            {{ $i->name }} </a>
                                                    </li>
                                                @endcan
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endcan
                        @elseif(!$m->parent_menu_id)
                            @can($m->permission)
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ in_array(request()->route()->action['as'], $m->child()->pluck('route')->toArray()) ? 'collapsed active' : '' }}"
                                        href="#sidebar{{ $m->permission }}" data-bs-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="sidebar{{ $m->permission }}">
                                        <i class="{{ $m->icon }}"></i> <span
                                            data-key="t-dashboards">{{ $m->name }}</span>
                                    </a>
                                    <div class="collapse menu-dropdown {{ in_array(request()->route()->action['as'], $m->child()->pluck('route')->toArray()) ? 'show' : '' }}"
                                        id="sidebar{{ $m->permission }}">
                                        <ul class="nav nav-sm flex-column">
                                            @foreach ($m->child as $i)
                                                @can($i->permission)
                                                    <li class="nav-item">
                                                        <a href="{{ route($i->route) }}" wire:navigate
                                                            class="nav-link {{ Route::is($i->route) ? 'active' : '' }}"
                                                            data-key="t-analytics">
                                                            {{ $i->name }} </a>
                                                    </li>
                                                @endcan
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endcan
                        @endif
                    @endforeach
                @endif

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link"><i class="ri-logout-box-r-line"></i>
                        Pergi ke Halaman Depan </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
