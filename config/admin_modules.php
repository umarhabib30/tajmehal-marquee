<?php

return [
    /*
    | Keys must match permission checks in config/route_permissions.php
    */
    'modules' => [
        'dashboard' => 'Dashboard',
        'customers' => 'Customers',
        'booking' => 'Booking',
        'dishes' => 'Dishes',
        'dish_packages' => 'Dish packages',
        'inventory' => 'Inventory',
        'staff' => 'Staff',
        'attendance' => 'Attendance',
        'salary' => 'Salary',
        'analysis' => 'Analysis',
    ],

    'actions' => [
        'view' => 'View',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    /*
    | First path used after login for staff when dashboard is not allowed.
    */
    'landing_paths' => [
        'dashboard' => '/admin/dashboard',
        'customers' => '/admin/customer/index',
        'booking' => '/admin/booking',
        'dishes' => '/admin/dishes/index',
        'dish_packages' => '/admin/dish_package',
        'inventory' => '/admin/inventory/index',
        'staff' => '/admin/staff/index',
        'attendance' => '/admin/attendence',
        'salary' => '/admin/salary',
        'analysis' => '/admin/analysis/bookings',
    ],
];
