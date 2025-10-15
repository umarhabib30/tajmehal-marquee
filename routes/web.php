<?php

use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\BookingController;
use App\Http\Controllers\admin\CalendarController;

use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\DishController;
use App\Http\Controllers\admin\InventoryController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
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


    Route::prefix('admin/booking')->name('booking.')->group(function () {
        Route::get('index', [BookingController::class, 'index'])->name('index');
        Route::get('create', [BookingController::class, 'create'])->name('create');
        Route::post('store', [BookingController::class, 'store'])->name('store');
        Route::get('edit/{id}', [BookingController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [BookingController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [BookingController::class, 'destroy'])->name('delete'); // ✅ fixed
        Route::get('show/{id}', [BookingController::class, 'show'])->name('show');
        Route::get('get-customer/{id}', [BookingController::class, 'getCustomer'])->name('getCustomer');
    });





    Route::prefix('admin')->group(function () {
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    });


    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance/bulk-save', [AttendanceController::class, 'bulkSave'])->name('attendance.bulkSave');
        Route::delete('attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    });
  
});
