<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Riska Mebel</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('frontend/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('frontend/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/js/select.dataTables.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="{{ asset('frontend/css/vertical-layout-light/style.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('image/logo-mini.png') }}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center mb-4">
                <img src="{{ asset('image/logo.png') }}" alt="logo" width="100">
              </div>
              <h4>Selamat Datang Kembali!</h4>
              <h6 class="font-weight-light">Login untuk melanjutkan.</h6>

              <!-- ✅ form login -->
              <form class="pt-3" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required>
                </div>

                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                </div>

                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
                </div>

                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" name="remember">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Lupa password?</a>
                </div>

              </form>
              <!-- ✅ end form -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="{{ asset('frontend/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('frontend/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('frontend/vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('frontend/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('frontend/js/dataTables.select.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{ asset('frontend/js/off-canvas.js') }}"></script>
  <script src="{{ asset('frontend/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('frontend/js/template.js') }}"></script>
  <script src="{{ asset('frontend/js/settings.js') }}"></script>
  <script src="{{ asset('frontend/js/todolist.js') }}"></script>
</body>
</html>
