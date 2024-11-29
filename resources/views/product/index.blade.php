<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h6 class="mb-0">Showing 1-24 of 205 Products</h6>
                </div>
                <div class="col-sm-auto">
                    <div class="row gx-2 align-items-center">
                        <div class="col-auto">
                            <form class="row gx-2">
                                <div class="col-auto"><small>Sort by:</small></div>
                                <div class="col-auto">
                                    <select class="form-select form-select-sm" aria-label="Bulk actions">
                                        <option selected="">Best Match</option>
                                        <option value="Refund">Newest</option>
                                        <option value="Delete">Price</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="col-auto pe-0">
                            <a class="text-600 px-1" href="../../../app/e-commerce/product/product-list.html"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Product List"><span
                                    class="fas fa-list-ul"></span></a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row" id="product-container">
                <!-- Produk akan dirender di sini oleh jQuery -->
            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
            {{-- <div>
                <button class="btn btn-falcon-default btn-sm me-2" type="button" disabled="disabled"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Prev">
                    <span class="fas fa-chevron-left"></span></button><a
                    class="btn btn-sm btn-falcon-default text-primary me-2" href="#!">1</a><a
                    class="btn btn-sm btn-falcon-default me-2" href="#!">2</a><a
                    class="btn btn-sm btn-falcon-default me-2" href="#!">
                    <span class="fas fa-ellipsis-h"></span></a><a class="btn btn-sm btn-falcon-default me-2"
                    href="#!">35</a><button class="btn btn-falcon-default btn-sm" type="button"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Next">
                    <span class="fas fa-chevron-right"> </span>
                </button>
            </div> --}}
            <nav>
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Kontrol pagination akan dirender di sini -->
                </ul>
            </nav>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Format angka ke Rupiah
                function formatRupiah(angka) {
                    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                // Fungsi untuk memuat produk dengan paginasi yang lebih baik
                function loadProducts(page = 1, itemsPerPage = 9) {
                    let url = '{{ route('product.list') }}';

                    $.ajax({
                        url: `${url}?page=${page}&per_page=${itemsPerPage}`,
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                let products = response.data;
                                let productContainer = $('#product-container');
                                const paginationContainer = $('#pagination');

                                // Kosongkan kontainer
                                productContainer.empty();
                                paginationContainer.empty();

                                // Render produk
                                products.forEach(function(product) {
                                    let productHtml = `
                        <div class="mb-4 col-md-6 col-lg-4">
                            <div class="border rounded-1 h-100 d-flex flex-column justify-content-between pb-3">
                                <div class="overflow-hidden">
                                    <div class="position-relative rounded-top overflow-hidden">
                                        <a class="d-block" href="#">
                                            <img class="img-fluid rounded-top" src="${product.image || '{{ asset('no-image.jpg') }}'}" alt="" />
                                        </a>
                                    </div>
                                    <div class="p-3">
                                        <h5 class="fs-9">
                                            <a class="text-1100" href="#">${product.nama_part}</a>
                                        </h5>
                                        <p class="fs-10 mb-3">
                                            <a class="text-500" href="#!">${product.group_tobpm || 'Unknown Category'}</a>
                                        </p>
                                        <h5 class="fs-md-2 text-warning mb-0 d-flex align-items-center mb-3">
                                            ${formatRupiah(product.standard_price_moving_avg_price)}
                                        </h5>
                                        <p class="fs-10 mb-1">
                                            Dealers: <strong>${product.dealer.ahass || 'Unknown Dealers'}</strong>
                                        </p>
                                        <p class="fs-10 mb-1">
                                            Stock: <strong class="text-${product.oh > 0 ? 'success' : 'danger'}">${product.oh > 0 ? product.oh : 'Out of Stock'}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-between-center px-3">
                                    <div>
                                        <a class="btn btn-sm btn-falcon-default me-2" href="#!" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Add to Wish List"><span class="far fa-heart"></span></a>
                                        <a class="btn btn-sm btn-falcon-default" href="#!" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Add to Cart"><span class="fas fa-cart-plus"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                                    productContainer.append(productHtml);
                                });

                                // Render kontrol paginasi dengan navigasi yang lebih baik
                                renderPagination(response.current_page, response.last_page);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching products:', error);
                            $('#product-container').html(
                                '<div class="alert alert-danger">Gagal memuat produk. Silakan coba lagi.</div>'
                                );
                        }
                    });
                }

                // Fungsi untuk merender kontrol paginasi dengan navigasi yang lebih canggih
                function renderPagination(currentPage, totalPages) {
                    const paginationContainer = $('#pagination');
                    paginationContainer.empty();

                    // Tombol Previous
                    if (currentPage > 1) {
                        paginationContainer.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);
                    }

                    // Logika untuk menampilkan nomor halaman secara cerdas
                    const range = 2; // Jumlah halaman di sekitar halaman saat ini
                    let start = Math.max(1, currentPage - range);
                    let end = Math.min(totalPages, currentPage + range);

                    // Tampilkan titik-titik jika ada halaman yang terlewat
                    if (start > 1) {
                        paginationContainer.append(`
                <li class="page-item disabled"><span class="page-link">...</span></li>
            `);
                    }

                    // Render nomor halaman
                    for (let i = start; i <= end; i++) {
                        const activeClass = i === currentPage ? 'active' : '';
                        paginationContainer.append(`
                <li class="page-item ${activeClass}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
                    }

                    // Tampilkan titik-titik jika ada halaman yang terlewat di akhir
                    if (end < totalPages) {
                        paginationContainer.append(`
                <li class="page-item disabled"><span class="page-link">...</span></li>
            `);
                    }

                    // Tombol Next
                    if (currentPage < totalPages) {
                        paginationContainer.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);
                    }

                    // Tambahkan event listener pada tombol pagination
                    $('.page-link').click(function(e) {
                        e.preventDefault();
                        const selectedPage = $(this).data('page');
                        if (selectedPage && !$(this).parent().hasClass('disabled')) {
                            loadProducts(selectedPage);
                        }
                    });
                }

                // Inisialisasi pemuatan produk
                loadProducts();
            });
        </script>
    @endpush
</x-templates.default>
