<!-- filepath: /d:/laragon/www/products-monitoring/resources/views/layouts/error.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link href="{{ asset('dist/vendors/overlayscrollbars/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/assets/css/theme-rtl.min.css') }}" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('dist/assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('dist/assets/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('dist/assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('dist/assets/css/custom-style.css') }}" rel="stylesheet" id="user-style-default">
</head>

<body>
    <main class="main" id="top">
        <div class="container" data-layout="container">
            <div class="row flex-center min-vh-100 py-6 text-center">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xxl-5">
                    <a class="d-flex flex-center mb-4" href="{{ url('/') }}"><img class="me-2"
                            src="{{ asset('dist/assets/img/logo-astra.png') }}" alt="" width="70%"/></a>
                    <div class="card">
                        <div class="card-body p-4 p-sm-5">
                            <div class="fw-black lh-1 text-300 fs-error">404</div>
                            {{-- <img class="img-fluid" src="{{ asset('dist/assets/img/error.gif') }}" alt="404 Error" width="50%" /> --}}
                            <p class="lead mt-4 text-800 font-sans-serif fw-semi-bold w-md-75 w-xl-100 mx-auto">
                                The page you're looking for is not found.
                            </p>
                            <hr />
                            <p>
                                Make sure the address is correct and that the page hasn't
                                moved.
                            </p>
                            @if (!auth()->check())
                                <a class="btn btn-primary btn-sm mt-3" href="{{ route('login') }}">Login</a>
                            @else
                            <a class="btn btn-primary btn-sm mt-3" href="{{ url('/') }}">Take me home</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
