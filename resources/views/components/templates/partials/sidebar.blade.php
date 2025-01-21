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
            <div class="d-flex align-items-center py-3">
                <img class="me-2" src="{{ asset('dist/assets/img/logo-astra.png') }}" alt="" width="100%" />
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                @foreach ($menus->where('parent_id', null) as $menu)
                    @can($menu->permission_name)
                        @php
                            // Periksa apakah ada submenu aktif
                            $hasActiveSubmenu = $menus
                                ->where('parent_id', $menu->id)
                                ->filter(fn($submenu) => request()->routeIs($submenu->route))
                                ->isNotEmpty();
                        @endphp

                        <li class="nav-item">
                            @if ($menus->where('parent_id', $menu->id)->isNotEmpty())
                                <!-- Menu Utama dengan Submenu -->
                                <a class="nav-link dropdown-indicator" href="#submenu-{{ $menu->id }}" role="button"
                                    data-bs-toggle="collapse" aria-expanded="{{ $hasActiveSubmenu ? 'true' : 'false' }}"
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
                                <ul class="nav collapse {{ $hasActiveSubmenu ? 'show' : '' }}"
                                    id="submenu-{{ $menu->id }}">
                                    @foreach ($menus->where('parent_id', $menu->id) as $submenu)
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
                                    href="{{ $menu->route ? route($menu->route) : '#' }}" role="button">
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
