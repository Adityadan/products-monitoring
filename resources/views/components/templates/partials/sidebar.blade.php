<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">

    <!-- Script untuk mengubah tampilan navbar -->
    {{-- <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script> --}}

    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>

        </div>
        <a class="navbar-brand" href="{{ route('dashboard.index') }}">
            <div class="d-flex align-items-center py-3">{{-- <img class="me-2" src="{{ asset('dist/assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" /> --}}<span
                    class="font-sans-serif">{{ env('APP_NAME') }}</span>
            </div>
        </a>
    </div>

    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            {{-- <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">
                                Dashboard</span>
                        </div>
                    </a>
                    </a><a class="nav-link dropdown-indicator" href="#product" role="button" data-bs-toggle="collapse"
                        aria-expanded="false" aria-controls="product">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-user-cog"></span></span><span
                                class="nav-link-text ps-1">Products</span>
                        </div>
                    </a>
                    <ul class="nav collapse" id="product">
                        <li class="nav-item"><a class="nav-link" href="{{ route('product.index') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">List
                                        Products</span>
                                </div>
                            </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('dealer-product.index') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Dealer
                                        Products</span></div>
                            </a></li>
                    </ul>
                    <a class="nav-link {{ request()->is('dealer') ? 'active' : '' }} "
                        href="{{ route('dealer.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-solid fa-store"></span></span><span class="nav-link-text ps-1">
                                List Dealer</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('users') ? 'active' : '' }}" href="{{ route('users.index') }}"
                        role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-users"></span></span><span class="nav-link-text ps-1">
                                Management User</span>
                        </div>
                    </a>
                    </a><a class="nav-link dropdown-indicator" href="#roles" role="button" data-bs-toggle="collapse"
                        aria-expanded="false" aria-controls="roles">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-user-cog"></span></span><span class="nav-link-text ps-1">Roles</span>
                        </div>
                    </a>
                    <ul class="nav collapse" id="roles">
                        <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Master
                                        Roles</span>
                                </div>
                            </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('roles.assign') }}">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Assign Roles
                                        to User</span></div>
                            </a></li>
                    </ul>
                </li>
                <a class="nav-link {{ request()->is('permissions') ? 'active' : '' }}"
                    href="{{ route('permissions.index') }}" role="button" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                class="fas fa-lock"></span></span><span class="nav-link-text ps-1">
                            Management Permission</span>
                    </div>
                </a>
                </li>
            </ul> --}}
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                @foreach ($menus->where('parent_id', null) as $menu)
                    <!-- Cek Permission untuk Menu Utama -->
                    @can($menu->permission_name)
                        <li class="nav-item">
                            @if ($menus->where('parent_id', $menu->id)->isNotEmpty())
                                <!-- Menu Utama dengan Submenu -->
                                <a class="nav-link dropdown-indicator" href="#submenu-{{ $menu->id }}" role="button"
                                    data-bs-toggle="collapse" aria-expanded="false"
                                    aria-controls="submenu-{{ $menu->id }}">
                                    <div class="d-flex align-items-center">
                                        @if ($menu->icon)
                                            <span class="nav-link-icon">
                                                <span class="{{ $menu->icon }}" style="color: {{ $menu->color }}"></span>
                                            </span>
                                        @endif
                                        <span class="nav-link-text ps-1">{{ $menu->name }}</span>
                                    </div>
                                </a>
                                <ul class="nav collapse" id="submenu-{{ $menu->id }}">
                                    @foreach ($menus->where('parent_id', $menu->id) as $submenu)
                                        <!-- Cek Permission untuk Submenu -->
                                        @can($submenu->permission_name)
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->routeIs($submenu->route) ? 'active' : '' }}"
                                                    href="{{ $submenu->route ? route($submenu->route) : '#' }}">
                                                    <div class="d-flex align-items-center">
                                                        @if ($submenu->icon)
                                                            <span class="nav-link-icon">
                                                                <span class="{{ $submenu->icon }}"
                                                                    style="color: {{ $submenu->color }}"></span>
                                                            </span>
                                                        @endif
                                                        <span class="nav-link-text ps-1">{{ $submenu->name }}</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            @else
                                <!-- Menu Utama Tanpa Submenu -->
                                <a class="nav-link {{ request()->routeIs($menu->route) ? 'active' : '' }}"
                                    href="{{ $menu->route ? route($menu->route) : '#' }}" role="button"
                                    aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        @if ($menu->icon)
                                            <span class="nav-link-icon">
                                                <span class="{{ $menu->icon }}"
                                                    style="color: {{ $menu->color }}"></span>
                                            </span>
                                        @endif
                                        <span class="nav-link-text ps-1">{{ $menu->name }}</span>
                                    </div>
                                </a>
                            @endif
                        </li>
                    @endcan
                @endforeach
            </ul>

        </div>
    </div>

</nav>
