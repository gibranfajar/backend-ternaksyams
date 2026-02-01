<!DOCTYPE html>
<html lang="en">

<!-- [Head] start -->
@include('components.head')
<!-- [Head] end -->


<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    @include('components.sidebar')
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    @include('components.navbar')
    <!-- [ Header ] end -->


    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->


    <!-- [ Footer ] start -->
    @include('components.footer')
    <!-- [ Footer ] end -->


    <!-- [ scripts ] start -->
    @include('components.scripts')
    @stack('scripts')
    <!-- [ scripts ] end -->
</body>
<!-- [Body] end -->

</html>
