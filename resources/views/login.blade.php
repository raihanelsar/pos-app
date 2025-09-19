<!DOCTYPE html>
<html lang="en">
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login | POS Admin</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA6VZgQAnGkW3k2Zr2Zl0O3oU5p4l9F6M1lX2h5Q5r5v5y" crossorigin="anonymous">
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('corona/assets/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('corona/assets/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('corona/assets/images/favicon.png') }}" />
  <!-- SweetAlert -->
  <link rel="stylesheet" href="{{ asset('corona/assets/sweetalert2/sweetalert2.min.css')}}">
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Login</h3>
                <form method="POST" id="loginForm" action="{{ route('login') }}">
                    @csrf
                  <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" value="{{ old('password') }}" id="password" class="form-control">
                  </div>
                  <div class="form-group d-flex align-items-center justify-content-between">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input"> Remember me </label>
                    </div>
                    <a href="#" class="forgot-pass">Forgot password</a>
                  </div>
                  <div class="text-center">
                    <button type="submit" value="login" id="loginButton" class="btn btn-primary btn-block enter-btn">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- plugins:js -->
  <script src="{{asset('corona/assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- Bootstrap 5 JS (bundle includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qQ2z5Tn5F5Q5r5v5yENjdO4Dr2bkBIFxQpeoA6VZgQAnGkW3k2Zr2Zl0O3oU5p4l9F6M1lX2h5Q5r5v5y" crossorigin="anonymous"></script>
  <!-- SweetAlert -->
  <script src="{{ asset('corona/assets/sweetalert2/sweetalert2.min.js')}}"></script>
  <!-- inject:js -->
  <script src="{{asset ('corona/assets/js/off-canvas.js') }}"></script>
  <script src="{{asset ('corona/assets/js/hoverable-collapse.js')  }}"></script>
  <script src="{{asset ('corona/assets/js/misc.js')  }}"></script>
  <script src="{{asset ('corona/assets/js/settings.js')  }}"></script>
  <script src="{{asset ('corona/assets/js/todolist.js')  }}"></script>
  <!-- endinject -->


    <script>
$(document).ready(() => {
    $("#loginForm").on("submit", (e) => {
        e.preventDefault();

        const token = $('meta[name="csrf-token"]').attr('content');

        const data = {
            email: $("#email").val(),
            password: $("#password").val()
        };

        $.ajax({
            type: 'POST',
            url: "{{ route('authLogin') }}",
            data: data,
            headers: { 'X-CSRF-TOKEN': token },
            success: (response) => {
                Swal.fire({
                    icon: 'success',
                    text: `Login Berhasil sebagai ${response.role}`,
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.location.href = response.redirectUrl;
                }, 1500);
            },
            error: (error) => {
                const message = error.responseJSON?.message || 'Terjadi kesalahan!';
                Swal.fire({ icon: 'error', text: message });
            }
        });
    });
});
</script>
  </body>
</html>
