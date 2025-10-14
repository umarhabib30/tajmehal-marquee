<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>{{ $title }}</title>
    <style>
        /* Default Navbar look */
        .dashboard-header nav.navbar {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .dashboard-header .navbar-brand {
            font-size: 1.15rem;
            font-weight: 600;
            white-space: nowrap;
            letter-spacing: 0.4px;
            text-transform: capitalize;
        }

        /* For tablets and below (below 992px) */
        @media (max-width: 992px) {
            .dashboard-header nav.navbar {
                padding-top: 4px;
                padding-bottom: 4px;
            }

            .dashboard-header .navbar-brand {
                font-size: 1rem;
                letter-spacing: 0.3px;
            }
        }

        /* For small screens (below 768px) */
        @media (max-width: 768px) {
            .dashboard-header nav.navbar {
                padding-top: 3px;
                padding-bottom: 3px;
            }

            .dashboard-header .navbar-brand {
                font-size: 0.95rem;
                letter-spacing: 0.2px;
            }
        }

        /* For extra small devices (below 480px) */
        @media (max-width: 480px) {
            .dashboard-header nav.navbar {
                padding-top: 2px;
                padding-bottom: 2px;
            }

            .dashboard-header .navbar-brand {
                font-size: 0.9rem;
                letter-spacing: 0.1px;
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
            <nav class="navbar navbar-expand-lg bg-white fixed-top" style="padding-top: 4px; padding-bottom: 4px;">
                <a class="navbar-brand" href="#">
                    Taj Mahal Marquee Shahpur
                </a>




                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="{{ asset('assets/images/avatar-1.jpg') }}" alt=""
                                    class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown"
                                aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name">Super Admin </h5>
                                </div>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                    class="items-center"><i class="fa fa-sign-out"></i>
                                    Logout</a>
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
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
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
                            <li class="nav-divider">
                                Menu
                            </li>
                            {{-- for dashbboard --}}

                            <li class="nav-item ">
                                <a class="nav-link @if ($active == 'Dashboard') active @endif"
                                    href="{{ url('admin/dashboard') }}"><i
                                        class="fa fa-fw fa-user-circle"></i>Dashboard <span
                                        class="badge badge-success">6</span></a>

                            </li>
                            <!-- Dishes Menu -->
                            <li class="nav-item">
                                <a class="nav-link" @if ($active == 'dishes') active @endif href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-dishes"
                                    aria-controls="submenu-dishes">
                                    <i class="fa fa-fw fa-paint-brush"></i> Dishes
                                    <span class="badge badge-success">6</span>
                                </a>
                                <div id="submenu-dishes" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/dishes/index') }}">View All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/dishes/create') }}">Add</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Customers Menu -->
                            <li class="nav-item">
                                <a class="nav-link" @if ($active == 'customers') active @endif href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-customers"
                                    aria-controls="submenu-customers">
                                    <i class="fa fa-fw fa-users"></i> Customers
                                    <span class="badge badge-primary">6</span>
                                </a>
                                <div id="submenu-customers" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/customer/index') }}">View All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/customer/create') }}">Add</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>



                            <!-- Staff Menu -->
                            <li class="nav-item">
                                <a class="nav-link" @if ($active == 'staff') active @endif href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-staff"
                                    aria-controls="submenu-staff">
                                    <i class="fa fa-fw fa-user-tie"></i> Staff
                                    <span class="badge badge-warning">6</span>
                                </a>
                                <div id="submenu-staff" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/staff/index') }}">View All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/staff/create') }}">Add</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Inventory Menu -->
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'inventory') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-inventory"
                                    aria-controls="submenu-inventory">
                                    <i class="fa fa-fw fa-boxes"></i> Inventory
                                    <span class="badge badge-info">5</span>
                                </a>
                                <div id="submenu-inventory" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('inventory.index') }}">View All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('inventory.create') }}">Add New</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Booking Menu -->
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'booking') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-booking"
                                    aria-controls="submenu-booking">
                                    <i class="fa fa-fw fa-calendar-check"></i> Booking
                                    <span class="badge badge-danger">New</span>
                                </a>
                                <div id="submenu-booking" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/booking/index') }}">View All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('admin/booking/create') }}">Add</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            {{-- Calendar --}}
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'calendar') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-calendar"
                                    aria-controls="submenu-calendar">
                                    <i class="fa fa-fw fa-calendar"></i> Calendar
                                    <span class="badge badge-success">New</span>
                                </a>
                                <div id="submenu-calendar" class="collapse submenu">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('calendar.index') }}">View
                                                Calendar</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>



{{-- for attendence --}}
<li class="nav-item">
    <a class="nav-link @if ($active == 'attendance') active @endif" href="#"
        data-toggle="collapse" aria-expanded="false" data-target="#submenu-attendance"
        aria-controls="submenu-attendance">
        <i class="fa fa-fw fa-user-check"></i> Attendance
        <span class="badge badge-success">New</span>
    </a>
    <div id="submenu-attendance" class="collapse submenu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('attendance.index') }}">View All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('attendance.create') }}">Add</a>
            </li>
        </ul>
    </div>
</li>


                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->

        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
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
    <script src="{{asset('https://code.jquery.com/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js')}}"></script>

    @yield('script')

</body>

</html>
