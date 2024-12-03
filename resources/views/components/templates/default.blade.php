<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<!-- Head -->
@includeIf('components.templates.partials.head')

<!-- Ternary class if route == survei.* -->

<body class="{{ Route::is('survei.*') ? 'body-survei' : '' }}">

    <!-- Main content -->
    <main class="main" id="top">
        <!-- Jika route bukan survei.* -->
            <div class="container" data-layout="container">
                <!-- Script untuk tampilan fluid -->
                <script>
                    var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                    if (isFluid) {
                        var container = document.querySelector('[data-layout]');
                        container.classList.remove('container');
                        container.classList.add('container-fluid');
                    }
                </script>
                <!-- Sidebar -->
                @includeIf('components.templates.partials.sidebar',['menus' => $menus])

                <div class="content">

                    <!-- Topbar -->
                    @includeIf('components.templates.partials.topbar')

                    <!-- Content -->
                    {{ $slot }}

                    <!-- Footer -->
                    @includeIf('components.templates.partials.footer')

                </div>

            </div>

    </main>

    <!-- modal disclaimer -->
    @yield('modal')

    <!-- End main content -->

    <!-- Scripts -->
    @includeIf('components.templates.partials.scripts')

</body>

</html>
