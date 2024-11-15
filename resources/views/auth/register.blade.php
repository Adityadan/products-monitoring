<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<!-- Head -->
@include('components.templates.partials.head')

<body>

    <!-- Content -->
    <main class="main" id="top">
        <div class="container" data-layout="container">
            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.remove('container');
                    container.classList.add('container-fluid');
                }
            </script>
            <div class="row flex-center min-vh-100 py-6">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4"><a class="d-flex flex-center mb-4"
                        href="../../../index.html">{{-- <img class="me-2"
                            src="../../../assets/img/icons/spot-illustrations/falcon.png" alt=""
                            width="58" /> --}}<span
                            class="font-sans-serif text-primary fw-bolder fs-4 d-inline-block">NDASMU PIYE</span></a>
                    <div class="card">
                        <div class="card-body p-4 p-sm-5">
                            <div class="row flex-between-center mb-2">
                                <div class="col-auto">
                                    <h5>Register</h5>
                                </div>
                                <div class="col-auto fs-10 text-600"><span class="mb-0 undefined">Have an
                                        account?</span> <span><a href="{{ route('login') }}">Login</a></span>
                                </div>
                            </div>
                            <form id="registerForm">
                                @csrf
                                <div class="mb-3"><input class="form-control" type="text" autocomplete="on"
                                        placeholder="Name" name="name" /></div>
                                <div class="mb-3"><input class="form-control" type="email" autocomplete="on"
                                        placeholder="Email address" name="email" /></div>
                                <div class="mb-3 "><input class="form-control" type="password" autocomplete="on"
                                        placeholder="Password" name="password" /></div>
                                <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-3" type=""
                                        name="submit" id="registerBtn">Register</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Content -->

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#registerBtn').click(function(e) {
                    e.preventDefault();

                    // Ambil data dari form
                    let formData = {
                        name: $("input[name=name]").val(),
                        email: $("input[name=email]").val(),
                        password: $("input[name=password]").val(),
                        _token: "{{ csrf_token() }}"
                    };

                    // Kirim data dengan AJAX
                    $.ajax({
                        url: "{{ route('register') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            // Jika berhasil, tampilkan notifikasi sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Registrasi Berhasil!',
                                text: 'Akun Anda berhasil dibuat.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Redirect atau reload setelah notifikasi
                                window.location.href = '{{ route('login') }}';
                            });
                        },
                        error: function(xhr) {
                            // Jika gagal, tampilkan notifikasi error
                            let errorMessage = "Registrasi gagal!";
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
    <!-- Scripts -->
    @include('components.templates.partials.scripts')

</body>

</html>
