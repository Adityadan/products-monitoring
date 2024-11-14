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
                        href="../../../index.html"><img class="me-2"
                            src="../../../assets/img/icons/spot-illustrations/falcon.png" alt=""
                            width="58" /><span
                            class="font-sans-serif text-primary fw-bolder fs-4 d-inline-block">falcon</span></a>
                    <div class="card">
                        <div class="card-body p-4 p-sm-5">
                            <div class="row flex-between-center mb-2">
                                <div class="col-auto">
                                    <h5>Log in</h5>
                                </div>
                                <div class="col-auto fs-10 text-600"><span class="mb-0 undefined">or</span> <span><a
                                            href="{{ route('register') }}">Create an
                                            account</a></span></div>
                            </div>
                            <form id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <input class="form-control" type="email" name="email"
                                        placeholder="Email address" required />
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="password" name="password" placeholder="Password"
                                        required />
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-block w-100 mt-3" type="" id="loginBtn">Log
                                        in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Content -->


    <!-- Scripts -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#loginBtn').click(function(e) {
                    e.preventDefault();
                    console.log('Login button clicked');

                    // Ambil data dari form
                    let formData = {
                        email: $("input[name=email]").val(),
                        password: $("input[name=password]").val(),
                        _token: "{{ csrf_token() }}"
                    };

                    // Kirim data dengan AJAX
                    $.ajax({
                        url: "{{ route('login') }}", // Pastikan route 'login' benar
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            // Jika berhasil, tampilkan notifikasi sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil!',
                                text: 'Anda berhasil masuk.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Redirect setelah notifikasi
                                window.location.href = '{{ route('list-product') }}'; // Ganti dengan halaman tujuan setelah login
                            });
                        },
                        error: function(xhr) {
                            // Jika gagal, tampilkan notifikasi error
                            let errorMessage = xhr.responseJSON.message ||
                                "Login gagal. Periksa kembali email atau password Anda!";
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
    @include('components.templates.partials.scripts')

</body>

</html>
