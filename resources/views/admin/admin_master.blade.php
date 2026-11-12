
<!doctype html>
<html lang="en">
<!--begin::Head-->
<head>
    @include('admin.body.header')
</head>
<!--end::Head-->
<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<!--begin::App Wrapper-->
<div class="app-wrapper">
    <!--begin::Header-->
    @include('admin.body.navbar')
    <!--end::Header-->
    <!--begin::Sidebar-->
    @include('admin.body.sidebar')
    <!--end::Sidebar-->
    <!--begin::App Main-->
    @yield('content')
    <!--end::App Main-->
    <!--begin::Footer-->
    @include('admin.body.footer')
    <!--end::Footer-->
</div>
<!--end::App Wrapper-->
<!--begin::Script-->
@include('admin.body.script')
<!--end::Script-->
</body>
<!--end::Body-->
</html>
