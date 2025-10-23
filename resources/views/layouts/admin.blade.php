<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/vendor/fonts/circular-std/style.css" rel="stylesheet') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ 'assets/vendor/fonts/fontawesome/css/fontawesome-all.css' }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts/chartist-bundle/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts/morris-bundle/morris.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts/c3charts/c3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icon-css/flag-icon.min.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('../assets/vendor/datatables/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('../assets/vendor/datatables/css/buttons.bootstrap4.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('../assets/vendor/datatables/css/select.bootstrap4.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('../assets/vendor/datatables/css/fixedHeader.bootstrap4.css') }}" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



    <title>{{ $title }}</title>

    <style>
        /* =========================
   GLOBAL NAVBAR STYLE
========================== */
        .dashboard-header nav.navbar {
            padding-top: 6px;
            padding-bottom: 6px;
            background-color: #29166f !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            flex-wrap: wrap;
            /* allow wrapping on small screens */
        }

        .dashboard-header .navbar-brand {
            font-size: 1.15rem;
            font-weight: 600;
            color: #fff !important;
            white-space: nowrap;
            letter-spacing: 0.4px;
            text-transform: capitalize;
        }

        .dashboard-header .nav-link {
            color: #fff !important;
        }

        .dashboard-header .nav-link:hover {
            color: #f5c542 !important;
        }

        .nav-user-dropdown .nav-user-info {
            background-color: #29166f;
            padding: 10px;
        }

        .nav-user-dropdown .nav-user-name {
            color: #fff;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, .1);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* Make nav-items stack on smaller screens */
        @media (max-width: 992px) {
            .dashboard-header nav.navbar {
                padding-top: 4px;
                padding-bottom: 4px;
            }

            .dashboard-header .navbar-brand {
                font-size: 1rem;
            }

            .navbar-collapse {
                text-align: center;
            }

            .navbar-nav {
                margin-top: 10px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header .navbar-brand {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-header .navbar-brand {
                font-size: 0.9rem;
            }

            .nav-user-dropdown .nav-user-info {
                padding: 8px;
            }
        }
    </style>


</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg fixed-top">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo"
                        style="height: 40px; width: auto; margin-right: 50px; border-radius: 5px;">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/images/avatar-1.jpg') }}" alt=""
                                    class="user-avatar-md rounded-circle border border-white">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown"
                                aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 nav-user-name">Super Admin</h5>
                                </div>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                    class="dropdown-item text-dark">
                                    <i class="fa fa-sign-out-alt mr-2 text-dark"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- ============================================================== -->
        <!-- Sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">Menu</li>

                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'Dashboard') active @endif"
                                    href="{{ url('admin/dashboard') }}"><i
                                        class="fa fa-fw fa-user-circle"></i>Dashboard
                                    <span class="badge badge-success">6</span></a>
                            </li>
                            {{-- for dishes --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'dishes') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-dishes"
                                    aria-controls="submenu-dishes">
                                    <i class="fa fa-fw fa-paint-brush"></i> Dishes
                                </a>
                                <div id="submenu-dishes" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('admin/dishes/index') }}">View All</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('admin/dishes/create') }}">Add</a></li>
                                    </ul>
                                </div>
                            </li>
                            {{-- for dish packages --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'dish-packages') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-dish-packages"
                                    aria-controls="submenu-dish-packages">
                                    <i class="fa fa-fw fa-box"></i> Dish Packages
                                </a>
                                <div id="submenu-dish-packages" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('admin.dish_package.index') }}">View
                                                All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/dish_package/create') }}">Add</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            {{-- for customer --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'customers') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-customers"
                                    aria-controls="submenu-customers">
                                    <i class="fa fa-fw fa-users"></i> Customers
                                </a>
                                <div id="submenu-customers" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('admin/customer/index') }}">View All</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('admin/customer/create') }}">Add</a></li>
                                    </ul>
                                </div>
                            </li>
                            {{-- for satff --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'staff') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-staff"
                                    aria-controls="submenu-staff">
                                    <i class="fa fa-fw fa-user-tie"></i> Staff
                                </a>
                                <div id="submenu-staff" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('admin/staff/index') }}">View All</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ url('admin/staff/create') }}">Add</a></li>
                                    </ul>
                                </div>
                            </li>
                            {{-- for inventory --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'inventory') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-inventory"
                                    aria-controls="submenu-inventory">
                                    <i class="fa fa-fw fa-boxes"></i> Inventory
                                </a>
                                <div id="submenu-inventory" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('inventory.index') }}">View All</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('inventory.create') }}">Add New</a></li>
                                    </ul>
                                </div>
                            </li>
                            {{-- for booking --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'booking') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-booking"
                                    aria-controls="submenu-booking">
                                    <i class="fa fa-fw fa-calendar-check"></i> Booking
                                </a>
                                <div id="submenu-booking" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('admin.bookings.index') }}">View All</a></li>
                                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('admin.bookings.create') }}">Add New</a></li>
                                    </ul>
                                </div>
                            </li>
                            {{-- for attendece --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'attendence') active @endif"
                                    href="{{ route('attendence.index') }}">
                                    <i class="fa fa-fw fa-user-check"></i> Attendance
                                </a>
                            </li>
                            {{-- for salaery --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'salary') active @endif"
                                    href="{{ route('admin.salary.index') }}">
                                    <i class="fa fa-fw fa-money-bill"></i> Salary
                                </a>
                            </li>





                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Main Content -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-header">
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#"
                                                    class="breadcrumb-link">Dashboard</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                {{ $heading }}</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <!-- bootstap bundle js -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- slimscroll js -->
    <script src="{{ asset('assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('assets/libs/js/main-js.js') }}"></script>
    <!-- chart chartist js -->
    <script src="{{ asset('assets/vendor/charts/chartist-bundle/chartist.min.js') }}"></script>
    <!-- sparkline js -->
    <script src="{{ asset('assets/vendor/charts/sparkline/jquery.sparkline.js') }}"></script>
    <!-- morris js -->
    <script src="{{ asset('assets/vendor/charts/morris-bundle/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/charts/morris-bundle/morris.js') }}"></script>
    <!-- chart c3 js -->
    <script src="{{ asset('assets/vendor/charts/c3charts/c3.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/charts/c3charts/d3-5.4.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/charts/c3charts/C3chartjs.js') }}"></script>
    <script src="{{ asset('assets/libs/js/dashboard-ecommerce.js') }}"></script>



    <script src="{{ asset('../assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('../assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('../assets/vendor/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('../assets/vendor/datatables/js/data-table.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js') }}"></script>



    @yield('script')

</body>

</html>
