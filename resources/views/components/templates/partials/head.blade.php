<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ $title ?? 'Untitle' }} </title>

    <!-- Jquery CDN -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script> -->
    <script src="{{ asset('dist/assets/js/jquery-3.6.1.min.js') }}"></script>

    {{-- <script>
        $(document).ready(function() {
            $(".js-select2").select2({
                theme: "bootstrap-5",
                width: "100%",
            });
        });
    </script> --}}

    <!-- Favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dist/assets/img/logo-astra.png') }}">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('dist/assets/img/logo-astra.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('dist/assets/img/logo-astra.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('dist/assets/img/logo-astra.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('dist/assets/img/logo-astra.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dist/assets/img/logo-astra.png') }}">
    {{-- <link rel="manifest" href="{{ asset('dist/assets/img/favicons/manifest.json') }}"> --}}
    <meta name="msapplication-TileImage" content="{{ asset('dist/assets/img/logo-astra.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('dist/assets/js/config.js') }}"></script>
    <script src="{{ asset('dist/vendors/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>

    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- notyf --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Load CSS -->
    <!-- IF current routes is survei.* -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('dist/vendors/overlayscrollbars/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/assets/css/theme-rtl.min.css') }}" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('dist/assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('dist/assets/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('dist/assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('dist/assets/css/custom-style.css') }}" rel="stylesheet" id="user-style-default">

    {{-- DATATABLE --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- SELECT 2 FALCON TEMPLATE --}}
    <link href="{{ asset('dist/vendors/choices/choices.min.css') }}" rel="stylesheet" />

    {{-- DropzoneJS --}}
    <link href="{{ asset('dist/vendors/dropzone/dropzone.min.css') }}" rel="stylesheet" />

    {{-- Toast --}}
    <link href="{{ asset('dist/vendors/toastr/toastr.min.css') }}" rel="stylesheet" />

    {{-- datepicker --}}
    <link href="{{ asset('dist/vendors/flatpickr/flatpickr2.min.css') }}" rel="stylesheet" />

    {{-- Month Select Plugin --}}
    <link href="{{ asset('dist/vendors/flatpickr/monthSelect/style.css') }}" rel="stylesheet" />





    <!-- Script untuk mengubah arah tampilan dari kanan ke kiri -->
    <script>
        var isRTL = JSON.parse(localStorage.getItem('isRTL'));
        if (isRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
    @stack('styles')

</head>
