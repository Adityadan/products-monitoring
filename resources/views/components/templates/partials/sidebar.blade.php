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

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>

        </div>
         <a class="navbar-brand" href="index.html">
            <div class="d-flex align-items-center py-3">{{-- <img class="me-2" src="{{ asset('dist/assets/img/icons/spot-illustrations/falcon.png') }}" alt="" width="40" /> --}}<span class="font-sans-serif">NDASMU</span>
        </div>
        </a>
        {{-- <div class="navbar-brand">
            <div class="d-flex align-items-center py-3 fs-1 text-danger text-uppercase">

            </div>
        </div> --}}
    </div>

    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                <li class="nav-item">
                    {{-- <a class="nav-link"  aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-home"></span></span><span class="nav-link-text ps-1">Beranda</span>
                        </div>
                    </a> --}}
                    <!-- <a class="nav-link dropdown-indicator" href="#dashboard" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="dashboard">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">Dashboard</span>
                        </div>
                    </a>
                    <ul class="nav collapse show" id="dashboard">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.html" aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Default</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="dashboard/analytics.html" aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Analytics</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="dashboard/crm.html" aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">CRM</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="dashboard/e-commerce.html" aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">E commerce</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="dashboard/project-management.html" aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Management</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="dashboard/saas.html" aria-expanded="false">
                                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">SaaS</span>
                                </div>
                            </a>
                        </li>
                    </ul> -->
                </li>
                <li class="nav-item">
                    <!-- label-->
                    {{-- <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Rekapitulasi</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div> --}}
                    <!-- Membuat div coming soon -->
                    <!-- <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fas-tools"></span></span><span class="nav-link-text ps-1">Dalam Perbaikan</span></div> -->
                    <a class="nav-link {{ request()->is('product') ? 'active' : ''}} " href="{{ route('product.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-solid fa-boxes"></span></span><span class="nav-link-text ps-1">
                                List Product</span>
                        </div>
                    </a>
                    <a class="nav-link {{ request()->is('dealer') ? 'active' : ''}} " href="{{ route('dealer.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-solid fa-store"></span></span><span class="nav-link-text ps-1">
                                List Dealer</span>
                        </div>
                    </a>
                </li>

                <!-- Extend list li bisa langsung dari sini -->
                <li class="nav-item">
                    <!-- label-->
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Modul</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider" />
                        </div>
                    </div>
                    <a class="nav-link {{ request()->is('user') ? 'active' : ''}}" href="{{-- {{ route('user.index') }} --}}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-users"></span></span><span class="nav-link-text ps-1">
                                Manajemen User</span>
                        </div>
                    </a>
                    <a class="nav-link {{ request()->is('roles') ? 'active' : ''}}" href="{{ route('roles.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user-cog"></span></span><span class="nav-link-text ps-1">
                                Manajemen Role</span>
                        </div>
                    </a>
                    <a class="nav-link {{ request()->is('permissions') ? 'active' : ''}}" href="{{ route('permissions.index') }}" role="button" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-lock"></span></span><span class="nav-link-text ps-1">
                                Manajemen Permission</span>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- <div class="settings mb-3">
                <div class="card alert p-0 shadow-none" role="alert">
                    <div class="btn-close-falcon-container">
                        <div class="btn-close-falcon" aria-label="Close" data-bs-dismiss="alert"></div>
                    </div>
                    <div class="card-body text-center"><img src="assets/img/icons/spot-illustrations/navbar-vertical.png" alt="" width="80" />
                        <p class="fs--2 mt-2">Loving what you see? <br />Get your copy of <a href="#!">Falcon</a></p>
                        <div class="d-grid"><a class="btn btn-sm btn-purchase" href="https://themes.getbootstrap.com/product/falcon-admin-dashboard-webapp-template/" target="_blank">Purchase</a></div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

</nav>
