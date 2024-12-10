<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-between align-items-center mb-3">
                <!-- Informasi Produk -->
                <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
                    <h6 class="mb-0">
                        Showing <span id="current-range">1-24</span> of <span id="total-products">205</span> Products
                    </h6>
                </div>

                <!-- Filter & Sort -->
                <div class="col-md-6 col-sm-12">
                    <div class="d-flex flex-wrap justify-content-md-end justify-content-start gap-2 align-items-center">
                        <!-- Filter By Stock -->
                        {{-- <div class="d-flex align-items-center">
                            <small class="me-2">Stock:</small>
                            <select class="form-select form-select-sm multiple-select" id="no_part" name="no_part[]" multiple="multiple">
                                @foreach ($no_part as $item)
                                    <option value="{{ $item['no_part'] }}">{{ $item['no_part'] }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <!-- Sort by -->
                        <div class="d-flex align-items-center">
                            <small class="me-2">Sort by:</small>
                            <select class="form-select form-select-sm" aria-label="" id="sort">
                                @foreach ($filters as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#filter-modal">Filter</button>
                        </div>

                        <!-- Filter Stock -->
                        {{-- <div class="d-flex align-items-center">
                            <small class="me-2">Stock:</small>
                            <select class="form-select form-select-sm" aria-label="Stock Filter" id="stock">
                                @foreach ($stock_filter as $item)
                                    <option value="{{ $item['value'] }}">{{ $item['text'] }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        {{-- <div class="card-header d-flex justify-content-center">
            <div class="input-group mt-2">
                <input class="form-control border rounded-pill" type="search" value="" id="search"
                    placeholder="Search Product Here ...">
            </div>
        </div> --}}
        <div class="card-body">
            <div class="row" id="product-container">
                <!-- Produk akan dirender di sini oleh jQuery -->
            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
            <nav>
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Kontrol pagination akan dirender di sini -->
                </ul>
            </nav>
        </div>
    </div>

    @include('product.modals')

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.multiple-select').select2({
                    theme: "bootstrap-5",
                });

                // Format angka ke Rupiah
                function formatRupiah(angka) {
                    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                // Fungsi untuk memuat produk dengan filter
                function loadProducts(page = 1, itemsPerPage = 9, searchQuery = '', sort = '', stock = '') {
                    // Ambil nilai filter jika tidak diberikan
                    searchQuery = searchQuery || $('#search').val();
                    sort = sort || $('#sort').val();
                    stock = stock || $('#stock').val();
                    no_part = $('#no_part').val();

                    let url = '{{ route('product.list') }}';

                    // Tampilkan loading SweetAlert
                    Swal.fire({
                        title: 'Loading...',
                        html: 'Please wait while we load the products.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            page: page,
                            per_page: itemsPerPage,
                            search: searchQuery,
                            sort: sort,
                            stock: stock,
                            no_part: no_part,
                            _token: '{{ csrf_token() }}' // CSRF token
                        },
                        success: function(response) {
                            Swal.close(); // Tutup SweetAlert loading

                            $('#current-range').html(response.current_range);
                            $('#total-products').html(response.total);

                            let productContainer = $('#product-container');
                            const paginationContainer = $('#pagination');

                            // Kosongkan kontainer
                            productContainer.empty();
                            paginationContainer.empty();

                            // Jika tidak ada produk, tampilkan pesan
                            if (response.success && response.data.length === 0) {
                                productContainer.html(
                                    '<div class="text-center">No products found.</div>'
                                );
                                return;
                            }

                            // Render produk
                            response.data.forEach(function(product) {
                                // Dapatkan gambar produk atau gunakan gambar default jika tidak ada
                                const productImage = product.product_images?.[0]?.image ?
                                    `{{ asset('storage/') }}/${product.product_images[0].image}` :
                                    `{{ asset('no-image.jpg') }}`;


                                // Template HTML produk
                                const productHtml = `
                                    <div class="mb-4 col-md-6 col-lg-4">
                                        <div class="border rounded-1 h-100 d-flex flex-column justify-content-between pb-3">
                                            <div class="overflow-hidden">
                                                <div class="position-relative rounded-top overflow-hidden">
                                                    <a class="d-block" href="#">
                                                        <img class="img-fluid rounded-top"
                                                            src="${productImage}"
                                                            alt="${product.nama_part || 'No Image'}" />
                                                    </a>
                                                </div>
                                                <div class="p-3">
                                                    <h5 class="fs-9">
                                                        <a class="text-1100" href="#">${product.nama_part || 'Unknown Part Name'}</a>
                                                    </h5>
                                                    <p class="fs-10 mb-3">
                                                        <a class="text-500" href="#!">${product.group_tobpm || 'Unknown Category'}</a>
                                                    </p>
                                                    <h5 class="fs-md-2 text-warning mb-0 d-flex align-items-center mb-3">
                                                        ${formatRupiah(product.standard_price_moving_avg_price) || 'Unknown Price'}
                                                    </h5>
                                                    <p class="fs-10 mb-1">
                                                        Code Part: <strong>${product.no_part || 'Unknown Code Part'}</strong>
                                                    </p>
                                                    <p class="fs-10 mb-1">
                                                        Dealers: <strong>${product.dealer?.ahass || 'Unknown Dealers'}</strong>
                                                    </p>
                                                    <p class="fs-10 mb-1">
                                                        Stock: <strong class="text-${product.oh > 0 ? 'success' : 'danger'}">
                                                            ${product.oh > 0 ? product.oh : 'Out of Stock'}
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                // Tambahkan HTML ke container
                                productContainer.append(productHtml);
                            });


                            // Render kontrol paginasi
                            renderPagination(response.current_page, response.last_page, searchQuery, sort,
                                stock);
                        },
                        error: function(xhr, status, error) {
                            Swal.close(); // Tutup SweetAlert loading
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to load products. Please reload the page.',
                            });
                            $('#product-container').html(
                                '<div class="alert alert-danger text-center">Failed to load products. Please Reload the Page.</div>'
                            );
                        }
                    });
                }


                // Event untuk pencarian dan pengurutan
                function debounce(func, delay) {
                    let timer;
                    return function(...args) {
                        clearTimeout(timer);
                        timer = setTimeout(() => func.apply(this, args), delay);
                    };
                }

                // Debounce untuk pencarian
                const debouncedSearch = debounce(function() {
                    let searchQuery = $('#search').val();
                    // loadProducts(1, 9, searchQuery); // Selalu mulai dari halaman 1 untuk pencarian
                    loadProducts(1);
                }, 800); // Delay 300ms

                // Event untuk pencarian dengan debounce
                $('#search').keyup(function(e) {
                    e.preventDefault();
                    debouncedSearch();
                });

                $('#sort').change(function() {
                    loadProducts(1);
                });

                $('#stock').change(function() {
                    loadProducts(1);
                });

                $("#no_part").change(function(e) {
                    e.preventDefault();
                    loadProducts(1);
                });
                // Inisialisasi
                loadProducts();


                // Fungsi untuk merender kontrol paginasi
                function renderPagination(currentPage, totalPages, searchQuery, sort, stock) {
                    const paginationContainer = $('#pagination');
                    paginationContainer.empty();

                    if (currentPage > 1) {
                        paginationContainer.append(`
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    `);
                    }

                    const range = 2;
                    let start = Math.max(1, currentPage - range);
                    let end = Math.min(totalPages, currentPage + range);

                    if (start > 1) {
                        paginationContainer.append(
                            '<li class="page-item disabled"><span class="page-link">...</span></li>');
                    }

                    for (let i = start; i <= end; i++) {
                        const activeClass = i === currentPage ? 'active' : '';
                        paginationContainer.append(`
                        <li class="page-item ${activeClass}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                    }

                    if (end < totalPages) {
                        paginationContainer.append(
                            '<li class="page-item disabled"><span class="page-link">...</span></li>');
                    }

                    if (currentPage < totalPages) {
                        paginationContainer.append(`
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    `);
                    }

                    $('.page-link').click(function(e) {
                        e.preventDefault();
                        const selectedPage = $(this).data('page');
                        if (selectedPage && !$(this).parent().hasClass('disabled')) {
                            loadProducts(selectedPage, 9, searchQuery, sort, stock);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-templates.default>
