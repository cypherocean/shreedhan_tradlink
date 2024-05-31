<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    @include('layouts.meta')
    <title>@yield('title') - ShreedhanTradeLink</title>

    @include('layouts.style')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns navbar-floating footer-static {{ \App\Helpers\AppHelper::themeClass()['body_class'] }}" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" {{ \App\Helpers\AppHelper::themeClass()['body_attributes'] }}>

    <!-- BEGIN: Header-->
    @include('layouts.header')
    <!-- END: Header-->

    @include('layouts.sidebar')

    @yield('content')

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('layouts.footer')


    @include('layouts.script')

</body>
<!-- END: Body-->

</html>