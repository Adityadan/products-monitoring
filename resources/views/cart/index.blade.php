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
                        Shopping</a><a class="btn btn-sm btn-primary"
                        href="../../app/e-commerce/checkout.html">Checkout</a>
                </div>
            </div>
        </div>
        <div class="card-body bg-body-tertiary">
            <div class="tab-content">
                <div class="tab-pane preview-tab-pane active" role="tabpanel"
                    aria-labelledby="tab-dom-b0aa3722-fa3d-4cce-b319-5c7f99be1924"
                    id="dom-b0aa3722-fa3d-4cce-b319-5c7f99be1924">
                    <div class="mb-3"><label class="form-label" for="exampleFormControlInput1">Nama</label><input class="form-control" id="exampleFormControlInput1" type="email"
                            placeholder="name@example.com" /></div>

                    <div class="mb-3"><label class="form-label" for="exampleFormControlInput1">Nomor Telepon</label><input class="form-control" id="exampleFormControlInput1" type="email"
                        placeholder="name@example.com" /></div>

                    <div class="mb-3"><label class="form-label" for="exampleFormControlTextarea1">Ekspedisi</label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected="">Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                          </select>
                    </div>
                    <div class="mb-3"><label class="form-label" for="exampleFormControlTextarea1">Alamat</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
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
            </form>
            <a class="btn btn-sm btn-primary" href="../../app/e-commerce/checkout.html">Checkout</a> --}}
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-md-auto">
                    <h5 class="mb-3 mb-md-0">Shopping Cart (7 Items)</h5>
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
            <div class="row gx-x1 mx-0 bg-200 text-900 fs-10 fw-semi-bold">
                <div class="col-9 col-md-8 py-2 px-x1">Name</div>
                <div class="col-3 col-md-4 px-x1">
                    <div class="row">
                        <div class="col-md-8 py-2 d-none d-md-block text-center">
                            Quantity
                        </div>
                        <div class="col-12 col-md-4 text-end py-2">Price</div>
                    </div>
                </div>
            </div>
            <div class="cart_content">
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
                <div class="col-9 col-md-8 py-2 px-x1 text-end text-900">
                    Total
                </div>
                <div class="col px-0">
                    <div class="row gx-x1 mx-0">
                        <div class="col-md-8 py-2 px-x1 d-none d-md-block text-center">
                            7 (items)
                        </div>
                        <div class="col-12 col-md-4 text-end py-2 px-x1">$8516</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-end">
            <form class="me-3">
                <div class="input-group input-group-sm">
                    <input class="form-control" type="text" placeholder="Promocode" /><button
                        class="btn btn-outline-secondary border-300 btn-sm shadow-none" type="submit">
                        Apply
                    </button>
                </div>
            </form>
            <a class="btn btn-sm btn-primary" href="../../app/e-commerce/checkout.html">Checkout</a>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {

        });
        function loadCart() {
            $.ajax({
                type: "GET",
                url: "url",
                data: "data",
                dataType: "dataType",
                success: function (response) {

                }
            });
        }
    </script>
    @endpush

</x-templates.default>
