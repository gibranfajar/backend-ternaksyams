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

    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->


    <!-- [ scripts ] start -->
    @include('components.scripts')
    <!-- [ scripts ] end -->
</body>
<!-- [Body] end -->

</html>
