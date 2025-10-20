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
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/get-customer/{id}', [BookingController::class, 'getCustomer'])->name('booking.getCustomer');
    Route::get('/booking/show/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/edit/{id}', [BookingController::class, 'edit'])->name('booking.edit');
    Route::post('/booking/update/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::post('/booking/delete/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
});




Route::prefix('admin')->group(function () {
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    });




Route::prefix('admin')->group(function () {
    // Attendance list
    Route::get('attendence', [AttendenceController::class, 'index'])->name('attendence.index');

    // Edit form (optional, if using modal or separate page)
    Route::get('attendence/edit/{id}', [AttendenceController::class, 'edit'])->name('attendence.edit');

    // Update attendance (POST instead of PUT)
    Route::post('attendence/update/{id}', [AttendenceController::class, 'update'])->name('attendence.update');

    // Store new attendance
    Route::post('attendence/store', [AttendenceController::class, 'store'])->name('attendence.store');

    // Mark entry/exit/leave
    Route::post('attendence/markEntry', [AttendenceController::class, 'markEntry'])->name('attendence.markEntry');
    Route::post('attendence/markExit', [AttendenceController::class, 'markExit'])->name('attendence.markExit');
    Route::post('attendence/markLeave', [AttendenceController::class, 'markLeave'])->name('attendence.markLeave');

    // View monthly report
    Route::get('attendence/view/{id}', [AttendenceController::class, 'view'])->name('attendence.view');

    // Delete attendance (POST instead of DELETE)
    Route::post('attendence/delete/{id}', [AttendenceController::class, 'delete'])->name('attendence.delete');
});



    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('salary', SalaryController::class);
    });








    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('dish_package',DishPackageController::class);
    });
});
