<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Riska Mebel</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('frontend/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/vendors/css/vendor.bundle.base.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- endinject -->

  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('frontend/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/js/select.dataTables.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <!-- End plugin css for this page -->

  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('frontend/css/vertical-layout-light/style.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
  <!-- endinject -->

  <link rel="shortcut icon" href="{{ asset('image/logo-mini.png') }}" />

  <!-- plugins:js -->
  <script src="{{ asset('frontend/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->

  <!-- Plugin js for this page -->
  <script src="{{ asset('frontend/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('frontend/vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('frontend/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('frontend/js/dataTables.select.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
  <!-- End plugin js for this page -->

  <!-- inject:js -->
  <script src="{{ asset('frontend/js/off-canvas.js') }}"></script>
  <script src="{{ asset('frontend/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('frontend/js/template.js') }}"></script>
  <script src="{{ asset('frontend/js/settings.js') }}"></script>
  <script src="{{ asset('frontend/js/todolist.js') }}"></script>
  <!-- endinject -->
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="{{ url('/') }}">
          <img src="{{ asset('image/logo.png') }}" class="mr-2" alt="logo" style="width: 150px; height: auto;" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
          <img src="{{ asset('frontend/images/logo-mini.svg') }}" alt="logo" />
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{ route('dashboard.karyawan') }}" class="d-flex align-items-center" style="line-height: 1;">
        <i class="mdi mdi-home" style="font-size: 1rem; margin-right: 5px; vertical-align: middle; margin-top: 2px;"></i> <!-- Ikon Home -->
      </a>
    </li>
    @yield('breadcrumb')
  </ol>
</nav>

        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="mdi mdi-information mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Stok hampir habis</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Jumlah stok kayu jati tersisa 10.
                  </p>
                </div>
              </a>
            </div>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="{{ asset('image/profile.jpeg') }}" alt="profile" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a href="{{ route('profile.edit') }}" class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Ubah Profile
            </a>            
              <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="ti-power-off text-primary"></i>
               Logout
           </a>

           <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form> 
        
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.karyawan') ? 'active' : '' }}" href="{{ route('dashboard.karyawan') }}">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('databahanbaku.karyawan') || request()->routeIs('edit.databahanbaku.karyawan') || request()->routeIs('create.databahanbaku.karyawan') ? 'active' : '' }}" href="{{ route('databahanbaku.karyawan') }}">
                    <i class="mdi mdi-clipboard-outline menu-icon"></i>
                    <span class="menu-title">Data Bahan Baku</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('stokmasuk.karyawan') || request()->routeIs('edit.stokmasuk.karyawan') || request()->routeIs('create.stokmasuk.karyawan') ? 'active' : '' }}" href="{{ route('stokmasuk.karyawan') }}">
                    <i class="mdi mdi-clipboard-outline menu-icon"></i>
                    <span class="menu-title">Stok Masuk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('stokkeluar.karyawan') || request()->routeIs('edit.stokkeluar.karyawan') || request()->routeIs('create.stokkeluar.karyawan') ? 'active' : '' }}" href="{{ route('stokkeluar.karyawan') }}">
                    <i class="mdi mdi-clipboard-outline menu-icon"></i>
                    <span class="menu-title">Stok Keluar</span>
                </a>
            </li>
        </ul>
      </nav>
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
      </div>
    </div>
  </div>
</body>
</html>