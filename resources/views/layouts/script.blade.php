<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
<!-- END: Page Vendor JS-->
<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    });

    const APP_URL = '{{ env("APP_URL") }}';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    @php
    $success = '';
    if (\Session::has('success'))
        $success = \Session::get('success');

    $error = '';
    if (\Session::has('error'))
        $error = \Session::get('error');
    @endphp

    var success = "{{ $success }}";
    var error = "{{ $error }}";

    if (success != '') {
        toastr.success(success, 'Success');
    }

    if (error != '') {
        toastr.error(error, 'error');
    }
</script>
<!-- BEGIN: Theme JS-->
<script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app.js') }}"></script>
<!-- END: Theme JS-->

<script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
@yield('script')