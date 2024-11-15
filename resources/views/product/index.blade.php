<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h6 class="mb-0">Showing {{ $products->count() }} of {{ $products->total() }} Products</h6>
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
            <div class="row">
                @foreach ($products as $product)
                    <div class="mb-4 col-md-6 col-lg-4">
                        <div class="border rounded-1 h-100 d-flex flex-column justify-content-between pb-3">
                            <div class="overflow-hidden">
                                <div class="position-relative rounded-top overflow-hidden">
                                    <a class="d-block" href="../../../app/e-commerce/product/product-details.html"><img
                                            class="img-fluid rounded-top" src="../../../assets/img/products/2.jpg"
                                            alt="" /></a>{{-- <span
                                    class="badge rounded-pill bg-success position-absolute mt-2 me-2 z-2 top-0 end-0">New</span> --}}
                                </div>
                                <div class="p-3">
                                    <h5 class="fs-9">
                                        <a class="text-1100"
                                            href="../../../app/e-commerce/product/product-details.html">{{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="fs-10 mb-3">
                                        <a class="text-500" href="#!">Computer &amp; Accessories</a>
                                    </p>
                                    <h5 class="fs-md-1 text-warning mb-0 d-flex align-items-center mb-3">
                                        ${{ number_format($product->price, 2) }}
                                    </h5>
                                    <p class="fs-10 mb-1">
                                        Shipping Cost: <strong>$50</strong>
                                    </p>
                                    <p class="fs-10 mb-1">
                                        Stock: <strong class="text-success">Available</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex flex-between-center px-3">
                                <div>
                                    <span class="fa fa-star text-warning"></span><span
                                        class="fa fa-star text-warning"></span><span
                                        class="fa fa-star text-warning"></span><span
                                        class="fa fa-star text-warning"></span><span
                                        class="fa fa-star text-300"></span><span class="ms-1">(8)</span>
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-falcon-default me-2" href="#!"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Wish List"><span
                                            class="far fa-heart"></span></a><a class="btn btn-sm btn-falcon-default"
                                        href="#!" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Add to Cart"><span class="fas fa-cart-plus"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer bg-body-tertiary d-flex justify-content-center">
            <div>
                {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}

            </div>
        </div>
    </div>
</x-templates.default>
