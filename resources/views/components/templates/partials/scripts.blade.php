<script src="{{ asset('dist/vendors/popper/popper.min.js') }}"></script>
<script src="{{ asset('dist/vendors/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('dist/vendors/anchorjs/anchor.min.js') }}"></script>
<script src="{{ asset('dist/vendors/is/is.min.js') }}"></script>
<script src="{{ asset('dist/vendors/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('dist/vendors/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('dist/vendors/lodash/lodash.min.js') }}"></script>
{{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script> --}}
<!-- <script src="{{ asset('dist/vendors/list.js/list.min.js') }}"></script> -->
<script src="{{ asset('dist/assets/js/theme.js') }}"></script>
<script src="{{asset('dist/vendors/swiper/swiper-bundle.min.js')}}"></script>
{{-- notyf --}}
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
{{-- SELECT 2 FALCON TEMPLATE --}}
<script src="{{ asset('dist/vendors/choices/choices.min.js') }}"></script>
{{-- DropzoneJS --}}
<script src="{{ asset('dist/vendors/dropzone/dropzone.min.js') }}"></script>
<!-- Toastr JS -->
<script src="{{ asset('dist/vendors/toastr/toastr.min.js') }}"></script>
{{-- DataTable --}}
<script src="{{ asset('dist/vendors/datatable/jquery.dataTables.min.js') }}"></script>
{{-- Sweet Alert --}}
<script src="{{ asset('dist/vendors/sweet-alert/sweetalert2@11.min.js') }}"></script>


@stack('scripts')
