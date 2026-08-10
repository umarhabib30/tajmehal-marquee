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
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
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

    @yield('style')

    <title>{{ $title }}</title>

    <style>
        html,
        body,
        button,
        input,
        select,
        textarea {
            font-family: sans-serif !important;
        }

        /* =========================
   GLOBAL NAVBAR STYLE
========================== */
        .dashboard-header {
            z-index: 1030;
        }

        .dashboard-header nav.navbar {
            padding: 6px 16px;
            background-color: #29166f !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            min-height: 64px;
        }

        .dashboard-header .navbar-brand {
            display: flex;
            align-items: center;
            margin-right: 0;
            padding: 0;
            color: #fff !important;
        }

        .dashboard-header .brand-logo {
            display: block;
            height: 40px;
            width: auto;
            max-width: min(180px, 42vw);
            border-radius: 5px;
        }

        .dashboard-header .header-menu-toggle {
            display: none;
        }

        .dashboard-header .navbar-toggler {
            margin-left: auto;
            padding: 0.35rem 0.55rem;
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.08);
        }

        .dashboard-header .navbar-toggler-icon {
            width: 1.35rem;
            height: 1.35rem;
            display: block;
            background-image: none;
            position: relative;
        }

        .dashboard-header .navbar-toggler-icon::before {
            content: "\f0c9";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #fff;
            font-size: 1.05rem;
            line-height: 1.35rem;
            display: block;
            text-align: center;
        }

        .dashboard-header .nav-link,
        .dashboard-header .nav-user-img {
            color: #fff !important;
        }

        .dashboard-header .nav-link:hover {
            color: #f5c542 !important;
        }

        .dashboard-header .navbar-collapse {
            flex-grow: 0;
        }

        .dashboard-header .navbar-right-top {
            align-items: center;
            margin-top: 0;
            gap: 0;
            margin-left: auto;
        }

        .dashboard-header .navbar-right-top .nav-item {
            border-right: 0;
        }

        .dashboard-header .navbar-right-top .nav-link {
            padding: 0;
        }

        .dashboard-header .nav-user-img {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
        }

        .dashboard-header .quotation-notification-nav {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .quotation-notification-toggle {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .quotation-notification-toggle:hover,
        .quotation-notification-toggle:focus {
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.55);
            outline: 0;
        }

        .quotation-notification-toggle.has-quotations .fa-bell {
            color: #ffd23f;
            transform-origin: top center;
            animation: quotation-bell-blink 1.15s ease-in-out infinite;
        }

        .quotation-notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 21px;
            height: 21px;
            padding: 0 5px;
            border: 2px solid #29166f;
            border-radius: 999px;
            background: #dc3545;
            color: #fff;
            font-size: 0.66rem;
            font-weight: 700;
            line-height: 1;
        }

        @keyframes quotation-bell-blink {
            0%, 100% {
                opacity: 1;
                transform: rotate(0deg) scale(1);
            }
            50% {
                opacity: 0.3;
                transform: rotate(12deg) scale(0.9);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .quotation-notification-toggle.has-quotations .fa-bell {
                animation: none;
            }
        }

        .quotation-notification-modal .modal-header {
            background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%);
            color: #fff;
            border-bottom: 0;
        }

        .quotation-notification-modal .modal-title {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #fff;
        }

        .quotation-notification-list {
            max-height: 360px;
            overflow-y: auto;
        }

        .quotation-notification-table th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #f2f0ff;
            color: #352a86;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .quotation-notification-table td {
            vertical-align: middle;
        }

        .quotation-notification-customer {
            font-weight: 700;
            color: #2f3547;
        }

        .nav-user-dropdown .nav-user-info {
            background-color: #29166f;
            padding: 10px;
        }

        .nav-user-dropdown .nav-user-name {
            color: #fff;
        }

        .nav-left-sidebar {
            height: calc(100vh - 60px);
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 12px;
        }

        .nav-left-sidebar .navbar {
            align-items: flex-start;
        }

        @media (max-width: 992px) {
            .dashboard-header nav.navbar {
                padding: 10px 12px;
                gap: 10px;
            }

            .dashboard-header .brand-logo {
                height: 34px;
                max-width: 140px;
            }

            .dashboard-header .header-menu-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .dashboard-header .navbar-right-top {
                display: none;
            }

            .nav-left-sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                width: 100%;
                height: auto;
                max-height: calc(100vh - 56px);
                overflow-y: auto;
                background: transparent;
                box-shadow: none;
                z-index: 1025;
            }

            .nav-left-sidebar .navbar {
                padding: 0;
            }

            .nav-left-sidebar .navbar-collapse {
                background: #0f1738;
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
            }

            .nav-left-sidebar .nav-divider {
                display: none;
            }

            .nav-left-sidebar .navbar-nav .nav-link {
                color: #c7d0ff;
            }

            .nav-left-sidebar .navbar-nav .nav-link:hover,
            .nav-left-sidebar .navbar-nav .nav-link:focus,
            .nav-left-sidebar .navbar-nav .nav-link.active {
                color: #fff;
                background-color: #242849;
            }

            .nav-left-sidebar .submenu {
                background: #161f47;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header nav.navbar {
                min-height: 56px;
            }

            .nav-left-sidebar {
                top: 56px;
                max-height: calc(100vh - 56px);
            }

            .nav-left-sidebar .submenu {
                padding-left: 8px;
                padding-right: 8px;
            }
        }

        @media (max-width: 480px) {
            .dashboard-header nav.navbar {
                padding: 10px;
            }

            .dashboard-header .brand-logo {
                height: 30px;
                max-width: 120px;
            }

            .dashboard-header .navbar-toggler {
                padding: 0.3rem 0.45rem;
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
                    <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo" class="brand-logo">
                </a>

                <button class="navbar-toggler header-menu-toggle" type="button" data-toggle="collapse"
                    data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <ul class="navbar-nav navbar-right-top d-none d-lg-flex">
                    <li class="nav-item quotation-notification-nav">
                        <button type="button"
                            class="quotation-notification-toggle {{ $quotationNotifications->isNotEmpty() ? 'has-quotations' : '' }}"
                            data-toggle="modal" data-target="#quotationNotificationsModal"
                            title="Quotation notifications" aria-label="Quotation notifications">
                            <i class="fas fa-bell" aria-hidden="true"></i>
                            @if ($quotationNotifications->isNotEmpty())
                                <span class="quotation-notification-count">{{ $quotationNotifications->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item dropdown nav-user">
                        <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/images/avatar-1.jpg') }}" alt=""
                                class="user-avatar-md rounded-circle border border-white">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right nav-user-dropdown"
                            aria-labelledby="navbarDropdownMenuLink2">
                            <div class="nav-user-info">
                                <h5 class="mb-0 nav-user-name">{{ auth()->user()->name ?? 'User' }}</h5>
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
            </nav>
        </div>

        <!-- ============================================================== -->
        <!-- Sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">Menu</li>

                            @superAdmin
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'users') active @endif"
                                        href="{{ route('admin.users.index') }}"><i
                                            class="fa fa-fw fa-user-shield"></i> Users &amp; permissions</a>
                                </li>
                            @endsuperAdmin

                            @moduleNav('dashboard')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'Dashboard') active @endif"
                                        href="{{ url('admin/dashboard') }}"><i
                                            class="fa fa-fw fa-user-circle"></i>Dashboard
                                        <span class="badge badge-success">6</span></a>
                                </li>
                            @endmoduleNav

                            @moduleNav('customers')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'customers') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false" data-target="#submenu-customers"
                                        aria-controls="submenu-customers">
                                        <i class="fa fa-fw fa-users"></i> Customers
                                    </a>
                                    <div id="submenu-customers" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('customers', 'view')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ url('admin/customer/index') }}">View All</a></li>
                                            @endmodulePerm
                                            @modulePerm('customers', 'create')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ url('admin/customer/create') }}">Add</a></li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            @moduleNav('booking')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'booking') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false" data-target="#submenu-booking"
                                        aria-controls="submenu-booking">
                                        <i class="fa fa-fw fa-calendar-check"></i> Booking
                                    </a>
                                    <div id="submenu-booking" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('booking', 'view')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('admin.booking.index') }}">View All</a></li>
                                            @endmodulePerm
                                            @modulePerm('booking', 'create')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('admin.booking.create') }}">Add New</a></li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            @moduleNav('dishes')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'dishes') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false" data-target="#submenu-dishes"
                                        aria-controls="submenu-dishes">
                                        <i class="fa fa-fw fa-paint-brush"></i> Dishes
                                    </a>
                                    <div id="submenu-dishes" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('dishes', 'view')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ url('admin/dishes/index') }}">View All</a></li>
                                            @endmodulePerm
                                            @modulePerm('dishes', 'create')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ url('admin/dishes/create') }}">Add</a></li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            @moduleNav('dish_packages')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'dish-packages') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false"
                                        data-target="#submenu-dish-packages" aria-controls="submenu-dish-packages">
                                        <i class="fa fa-fw fa-box"></i> Dish Packages
                                    </a>
                                    <div id="submenu-dish-packages" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('dish_packages', 'view')
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ route('admin.dish_package.index') }}">View All</a>
                                                </li>
                                            @endmodulePerm
                                            @modulePerm('dish_packages', 'create')
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ url('admin/dish_package/create') }}">Add</a>
                                                </li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            @moduleNav('inventory')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'inventory') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false" data-target="#submenu-inventory"
                                        aria-controls="submenu-inventory">
                                        <i class="fa fa-fw fa-boxes"></i> Inventory
                                    </a>
                                    <div id="submenu-inventory" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('inventory', 'create')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.create') }}">Add New</a></li>
                                            @endmodulePerm
                                            @modulePerm('inventory', 'view')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.index') }}">All Items</a></li>
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.food') }}">Food Items</a></li>
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.electronics') }}">Electronics
                                                        Items</a></li>
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.furniture') }}">Furniture Items</a>
                                                </li>
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.crockery') }}">Crockery Items</a>
                                                </li>
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ route('inventory.decoration') }}">Decoration
                                                        Items</a></li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            @moduleNav('staff')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'staff') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false" data-target="#submenu-staff"
                                        aria-controls="submenu-staff">
                                        <i class="fa fa-fw fa-user-tie"></i> Staff
                                    </a>
                                    <div id="submenu-staff" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('staff', 'view')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ url('admin/staff/index') }}">View All</a></li>
                                            @endmodulePerm
                                            @modulePerm('staff', 'create')
                                                <li class="nav-item"><a class="nav-link"
                                                        href="{{ url('admin/staff/create') }}">Add</a></li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            @moduleNav('attendance')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'attendence') active @endif"
                                        href="{{ route('attendence.index') }}">
                                        <i class="fa fa-fw fa-user-check"></i> Attendance
                                    </a>
                                </li>
                            @endmoduleNav

                            @moduleNav('salary')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'salary') active @endif"
                                        href="{{ route('admin.salary.index') }}">
                                        <i class="fa fa-fw fa-money-bill"></i> Salary
                                    </a>
                                </li>
                            @endmoduleNav

                            @moduleNav('analysis')
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'analysis') active @endif" href="#"
                                        data-toggle="collapse" aria-expanded="false" data-target="#analysis-submenu"
                                        aria-controls="analysis-submenu">
                                        <i class="fa fa-fw fa-chart-bar"></i> Analysis
                                    </a>
                                    <div id="analysis-submenu" class="collapse submenu">
                                        <ul class="nav flex-column">
                                            @modulePerm('analysis', 'view')
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ route('admin.analysis.booking') }}">Booking
                                                        Analysis</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ route('admin.analysis.inventory') }}">Inventory
                                                        Analysis</a>
                                                </li>
                                            @endmodulePerm
                                        </ul>
                                    </div>
                                </li>
                            @endmoduleNav

                            <li class="nav-item d-lg-none">
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                    class="nav-link">
                                    <i class="fa fa-fw fa-sign-out-alt"></i> Logout
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
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade quotation-notification-modal" id="quotationNotificationsModal" tabindex="-1"
        role="dialog" aria-labelledby="quotationNotificationsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationNotificationsTitle">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                        <span>Quotation Notifications</span>
                    </h5>
                </div>
                <div class="modal-body">
                    @if ($quotationNotifications->isNotEmpty())
                        <p class="text-muted mb-3">
                            {{ $quotationNotifications->count() }}
                            {{ \Illuminate\Support\Str::plural('quotation', $quotationNotifications->count()) }} awaiting follow-up.
                        </p>
                        <div class="table-responsive quotation-notification-list">
                            <table class="table table-bordered table-hover quotation-notification-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Hall / Time</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotationNotifications as $quotation)
                                        <tr>
                                            <td class="quotation-notification-customer">
                                                {{ $quotation->customer->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ $quotation->event_type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($quotation->event_date)->format('d-M-Y') }}</td>
                                            <td>{{ $quotation->hall_name }} / {{ $quotation->time_slot }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.booking.show', $quotation->id) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="far fa-bell-slash fa-2x mb-3 d-block"></i>
                            No quotation notifications.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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

    @if (session('error'))
        <script>
            toastr.error(@json(session('error')));
        </script>
    @endif

</body>

</html>
