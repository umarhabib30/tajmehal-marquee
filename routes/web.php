<?php

use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\AttendenceController;
use App\Http\Controllers\admin\BookingController;
use App\Http\Controllers\admin\CalendarController;

use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\DishController;
use App\Http\Controllers\admin\DishPackageController;
use App\Http\Controllers\admin\InventoryController;
use App\Http\Controllers\admin\SalaryController;

use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();



Route::middleware(['auth', 'admin_role'])->group(function () {

    Route::get('admin/dashboard', [DashboardController::class, 'index']);
    Route::get('/', [DashboardController::class, 'index']);



// for dishes
    Route::get('admin/dishes/index', [DishController::class, 'index'])->name('dishes.index');
    Route::get('admin/dishes/create', [DishController::class, 'create'])->name('dishes.create');
    Route::post('admin/dishes/store', [DishController::class, 'store'])->name('dishes.store');
    Route::get('admin/dishes/edit/{id}', [DishController::class, 'edit'])->name('dishes.edit');
    Route::post('admin/dishes/update', [DishController::class, 'update'])->name('dishes.update');
    Route::get('admin/dishes/destroy/{id}', [DishController::class, 'destroy'])->name('dishes.destroy');






    Route::get('admin/customer/index', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('admin/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('admin/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('admin/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('admin/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('admin/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');





    // Staff CRUD Routes
    Route::prefix('admin/staff')->name('staff.')->group(function () {
        Route::get('index', [StaffController::class, 'index'])->name('index');
        Route::get('create', [StaffController::class, 'create'])->name('create');
        Route::post('store', [StaffController::class, 'store'])->name('store');
        Route::get('edit/{id}', [StaffController::class, 'edit'])->name('edit');
        Route::post('update', [StaffController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [StaffController::class, 'destroy'])->name('destroy');
    });

    Route::get('admin/inventory/index', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('admin/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('admin/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('admin/inventory/edit/{inventory}', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('admin/inventory/update/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('admin/inventory/destroy/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');




    Route::prefix('admin')->name('admin.')->group(function () {

        // INDEX - show all bookings
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');

        // CREATE - show create form
        Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');

        // STORE - save new booking
        Route::post('bookings/store', [BookingController::class, 'store'])->name('bookings.store');

        // SHOW - show single booking
        Route::get('bookings/show/{id}', [BookingController::class, 'show'])->name('bookings.show');

        // EDIT - show edit form
        Route::get('bookings/edit/{id}', [BookingController::class, 'edit'])->name('bookings.edit');

        // UPDATE - update booking
        Route::post('bookings/update/{id}', [BookingController::class, 'update'])->name('bookings.update');

        // DELETE - delete booking
        Route::post('bookings/delete/{id}', [BookingController::class, 'destroy'])->name('bookings.delete');

        // AJAX routes
        Route::get('bookings/customer/{id}', [BookingController::class, 'getCustomer'])->name('bookings.getCustomer');
        Route::get('bookings/package-dishes/{id}', [BookingController::class, 'getPackageDishes'])->name('bookings.getPackageDishes');
    });


Route::prefix('admin')->group(function () {
       
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    });





Route::prefix('admin')->group(function () {
    // Attendance list
    Route::get('attendence', [AttendenceController::class, 'index'])->name('attendence.index');

    // View monthly report
    Route::get('attendence/view/{id}', [AttendenceController::class, 'view'])->name('attendence.view');

    // Store new attendance
    Route::post('attendence/store', [AttendenceController::class, 'store'])->name('attendence.store');

    // Update attendance (for leave/present checkbox)
    Route::post('attendence/update', [AttendenceController::class, 'update'])->name('attendence.update');

    // Delete attendance
    Route::post('attendence/delete/{id}', [AttendenceController::class, 'delete'])->name('attendence.delete');
        // AJAX update status (for checkboxes)
        Route::post('attendence/update-status', [AttendenceController::class, 'updateStatus'])->name('attendence.updateStatus');
});




    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('salary', [SalaryController::class, 'index'])->name('salary.index');          // list
        Route::get('salary/create', [SalaryController::class, 'create'])->name('salary.create'); // create form
        Route::post('salary', [SalaryController::class, 'store'])->name('salary.store');         // store salary
        Route::get('salary/{salary}', [SalaryController::class, 'show'])->name('salary.show');   // show slip
        Route::post('salary/{salary}/delete', [SalaryController::class, 'delete'])->name('salary.delete'); // delete salary
        Route::get('get-absent-days', [SalaryController::class, 'getAbsentDays'])->name('salary.getAbsentDays'); // AJAX fetch absent
    });



    Route::prefix('admin')->name('admin.')->group(function () {

        // List
        Route::get('dish_package', [DishPackageController::class, 'index'])
            ->name('dish_package.index');

        // Create form
        Route::get('dish_package/create', [DishPackageController::class, 'create'])
            ->name('dish_package.create');

        // Store
        Route::post('dish_package', [DishPackageController::class, 'store'])
            ->name('dish_package.store');

        // Edit form
        Route::get('dish_package/{id}/edit', [DishPackageController::class, 'edit'])
            ->name('dish_package.edit');

        // Update — NOTE: using POST (no PUT)
        Route::post('dish_package/{id}/update', [DishPackageController::class, 'update'])
            ->name('dish_package.update');

        // Delete — NOTE: using POST for AJAX delete
        Route::post('dish_package/{id}/delete', [DishPackageController::class, 'destroy'])
            ->name('dish_package.destroy');
    });
});
