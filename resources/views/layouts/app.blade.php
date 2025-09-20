<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>POS Admin</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap 5 CSS from CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/jvectormap/jquery-jvectormap.css') }}">
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
  <!-- End plugin css for this page -->

  {{-- DataTable (CDN) --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" crossorigin href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
  <link rel="stylesheet" href="/css/theme.css">
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('corona/assets/css/style.css') }}">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="{{ asset('corona/assets/images/favicon.png') }}" />
  <!-- SweetAlert (keep only minified) -->
  <link rel="stylesheet" href="{{ asset('corona/assets/sweetalert2/sweetalert2.min.css')}}">
</head>
<body>
  <div class="container-scroller">
    @include('layouts.partials.sidebar')

    <div class="container-fluid page-body-wrapper">
      @include('layouts.partials.navbar')

      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        @include('layouts.partials.footer')
      </div>
    </div>
  </div>

 <!-- plugins:js -->
<script src="{{ asset('corona/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('corona/assets/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('corona/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
  <script src="{{ asset('corona/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
  <script src="{{ asset('corona/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
  <script src="{{ asset('corona/assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
  <!-- End plugin js for this page -->
  <!-- jQuery (must be first) -->
  <script src="{{ asset('corona/assets/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 5 JS (bundle includes Popper) -->
  <!-- Bootstrap 5 JS from CDN (bundle includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <!-- DataTables core JS (CDN) -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  <!-- SweetAlert (keep only minified) -->
  <script src="{{ asset('corona/assets/sweetalert2/sweetalert2.min.js')}}"></script>
  <script>
    // Global AJAX CSRF header setup for jQuery
    (function(){
      var tokenMeta = document.querySelector('meta[name="csrf-token"]');
      if(tokenMeta && window.jQuery) {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': tokenMeta.getAttribute('content') } });
      }
    })();
  </script>
  <!-- inject:js -->
  <script src="{{ asset('corona/assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('corona/assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('corona/assets/js/misc.js') }}"></script>
  <script src="{{ asset('corona/assets/js/settings.js') }}"></script>
  <script src="{{ asset('corona/assets/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="{{ asset('corona/assets/js/dashboard.js') }}"></script>
  <!-- End custom js for this page -->
  <!-- Custom per-page -->
  @yield('modalEdit')
  @yield('modal')
  @yield('script')
</body>
</html>
