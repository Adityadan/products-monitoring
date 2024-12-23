<x-templates.default>

    <div class="card mb-3">
        <div class="card-header">
            {{-- <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0" data-anchor="data-anchor">Form Shipping</h5>
                </div>
            </div> --}}
            <div class="row justify-content-between">
                <div class="col-md-auto">
                    <h5 class="mb-0" data-anchor="data-anchor">Form Shipping</h5>
                </div>
                <div class="col-md-auto">
                    <a class="btn btn-sm btn-outline-secondary border-300 me-2 shadow-none"
                        href="{{ route('product.index') }}">
                        <span class="fas fa-chevron-left me-1" data-fa-transform="shrink-4"></span>Continue
                        Shopping</a>{{-- <a class="btn btn-sm btn-primary"
                        href="../../app/e-commerce/checkout.html">Checkout</a> --}}
                </div>
            </div>
        </div>
        <div class="card-body bg-body-tertiary">
            <div class="tab-content">
                <div class="tab-pane preview-tab-pane active">
                    <form id="checkout-form">
                        <div class="mb-3"><label class="form-label" for="nama">Nama</label><input
                                class="form-control" id="name" name="name" type="text" placeholder="masukkan nama" /></div>

                        <div class="mb-3"><label class="form-label" for="phone">Nomor Telepon</label><input
                                class="form-control" id="phone" type="number" name="phone"
                                placeholder="masukkan nomor telepon" />
                        </div>
                        <div class="mb-3"><label class="form-label" for="alamat">Alamat</label>
                            <textarea class="form-control" id="address" rows="3" placeholder="masukkan alamat" name="address"
                                id="address"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-end">
            {{-- <form class="me-3">
                <div class="input-group input-group-sm">
                    <input class="form-control" type="text" placeholder="Promocode" /><button
                        class="btn btn-outline-secondary border-300 btn-sm shadow-none" type="submit">
                        Apply
                    </button>
                </div>
            </form> --}}
            <a class="btn btn-sm btn-success" id="btn-checkout"><i class="fas fa-check me-1"></i>Simpan</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-md-auto">
                    <h5 class="mb-3 mb-md-0" id="cart-header-total">Shopping Cart (7 Items)</h5>
                </div>
                {{-- <div class="col-md-auto">
                    <a class="btn btn-sm btn-outline-secondary border-300 me-2 shadow-none"
                        href="{{ route('product.index') }}">
                        <span class="fas fa-chevron-left me-1" data-fa-transform="shrink-4"></span>Continue
                        Shopping</a><a class="btn btn-sm btn-primary"
                        href="../../app/e-commerce/checkout.html">Checkout</a>
                </div> --}}
            </div>
        </div>
        <div class="card-body p-0">
            {{-- <div class="row gx-x1 mx-0 bg-200 text-900 fs-10 fw-semi-bold">
                <div class="col-9 col-md-8 py-2 px-x1">Name</div>
                <div class="col-3 col-md-4 px-x1">
                    <div class="row">
                        <div class="col-md-8 py-2 d-none d-md-block text-center">
                            Quantity
                        </div>
                        <div class="col-12 col-md-4 text-end py-2">Price</div>
                    </div>
                </div>
            </div> --}}

            <div class="row gx-x1 mx-0 bg-200 text-900 fs-10 fw-semi-bold">
                <div class="col-6 col-md-6 py-2 px-x1">Name</div>
                <div class="col-6 col-md-6 px-x1">
                    <div class="row">
                        <div class="col-md-4 py-2 d-none d-md-block text-center">
                            Quantity
                        </div>
                        <div class="col-md-4 text-end py-2">Price</div>
                        <div class="col-md-4 text-end py-2">Sub Total</div>
                    </div>
                </div>
            </div>

            <div class="cart_content" id="cart-content">
                {{-- <div class="row gx-x1 mx-0 align-items-center border-bottom border-200">
                    <div class="col-8 py-3 px-x1">
                        <div class="d-flex align-items-center">
                            <a href="../../app/e-commerce/product/product-details.html"><img
                                    class="img-fluid rounded-1 me-3 d-none d-md-block"
                                    src="../../assets/img/products/1.jpg" alt="" width="60" /></a>
                            <div class="flex-1">
                                <h5 class="fs-9">
                                    <a class="text-900" href="../../app/e-commerce/product/product-details.html">Apple
                                        MacBook Pro 15&quot; Z0V20008N: 2.9GHz 6-core
                                        8th-Gen Intel Core i9, 32GB RAM</a>
                                </h5>
                                <div class="fs-11 fs-md--1">
                                    <a class="text-danger" href="#!"><i
                                            class="far fa-trash-alt me-1"></i>Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 py-3 px-x1">
                        <div class="row align-items-center">
                            <div
                                class="col-md-8 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                                <div>
                                    <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity">
                                        <button class="btn btn-sm btn-outline-secondary border-300 px-2 shadow-none"
                                            data-type="minus">
                                            -</button><input class="form-control text-center px-2 input-spin-none"
                                            type="number" min="1" value="1"
                                            aria-label="Amount (to the nearest dollar)" style="width: 50px" /><button
                                            class="btn btn-sm btn-outline-secondary border-300 px-2 shadow-none"
                                            data-type="plus">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                                $1292
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="row fw-bold gx-x1 mx-0">
                <div class="col-6 col-md-6 py-2 px-x1 text-end text-900">
                    Total
                </div>
                <div class="col-6 col-md-6 px-0">
                    <div class="row gx-x1 mx-0">
                        <div class="col-md-4 py-2 px-x1 d-none d-md-block text-center" id="total-items">
                            7 (items)
                        </div>
                        <div class="col-12 col-md-4 text-end py-2 px-x1" id="total-price"></div>
                        <div class="col-12 col-md-4 text-end py-2 px-x1" id="total">$8516</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-end">
            <form class="me-3">
                <div class="input-group input-group-sm">
                    {{-- <input class="form-control" type="text" placeholder="Promocode" /><button
                        class="btn btn-outline-secondary border-300 btn-sm shadow-none" type="submit">
                        Apply
                    </button> --}}
                </div>
            </form>
            {{-- <a class="btn btn-sm btn-success" href="../../app/e-commerce/checkout.html"><i class="fas fa-check me-1"></i>Simpan</a> --}}
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                loadCart();
                $('#btn-checkout').click(function(e) {
                    e.preventDefault();

                    let form = $('#checkout-form')[0]; // Mengambil elemen form
                    let data = new FormData(form);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('cart.checkout') }}",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false, // Jangan proses data
                        contentType: false, // Jangan tentukan tipe konten
                        dataType: "json",
                        success: function(response) {
                            // Tambahkan logika sukses di sini
                            if (response.success === true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('product.index') }}";
                                    }
                                });
                            }else
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to checkout. Please reload the page.',
                            });
                        }
                    });
                });
            });

            function loadCart() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('cart.load') }}",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let cart_content = response.cart_content;
                        let total_items = response.total.total_items;
                        let total = response.total.total_price;

                        $('#cart-content').html(cart_content);
                        $('#total-items').text(total_items);
                        $('#total').text(total);
                        $('#cart-header-total').text(`Shopping Cart (${total_items} Items)`);
                    }
                });
            }
        </script>
    @endpush

</x-templates.default>
