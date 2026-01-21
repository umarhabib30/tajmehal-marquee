<?php

use App\Http\Controllers\admin\AnalysisController;
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
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
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

    Route::get('admin/inventory/food', [InventoryController::class, 'foodInventory'])->name('inventory.food');
    Route::get('admin/inventory/decoration', [InventoryController::class, 'decorationInventory'])->name('inventory.decoration');
    Route::get('admin/inventory/furniture', [InventoryController::class, 'furnitureInventory'])->name('inventory.furniture');
    Route::get('admin/inventory/crockery', [InventoryController::class, 'crockeryInventory'])->name('inventory.crockery');
    Route::get('admin/inventory/electronics', [InventoryController::class, 'electronicsInventory'])->name('inventory.electronics');
    Route::get('admin/inventory/stock/{id}', [InventoryController::class, 'stockInventory'])->name('inventory.stock');
    Route::post('admin/inventory/stock/update', [InventoryController::class, 'updateStock'])->name('inventory.stock.update');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
        Route::get('booking/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('booking/store', [BookingController::class, 'store'])->name('booking.store');
        Route::get('booking/show/{id}', [BookingController::class, 'show'])->name('booking.show');
        Route::get('booking/edit/{id}', [BookingController::class, 'edit'])->name('booking.edit');
        Route::post('booking/update', [BookingController::class, 'update'])->name('booking.update');
       
        Route::get('booking/customer/{id}', [BookingController::class, 'getCustomer'])->name('booking.getCustomer');
        Route::get('booking/package-dishes/{id}', [BookingController::class, 'getPackageDishes'])->name('booking.getPackageDishes');
        Route::get('/booking/add-payment/{id}', [BookingController::class, 'addPaymentPage'])->name('booking.addPaymentPage');
        Route::post('/booking/add-payment', [BookingController::class, 'addPayment'])->name('booking.addPayment');
        Route::get('/bookings/invoice/{id}', [BookingController::class, 'invoice'])->name('booking.invoice');
        Route::get('/booking/create/{id}', [BookingController::class, 'create'])->name('booking.createWithCustomer');
        Route::post('booking/extra-guest', [BookingController::class, 'extraGuest'])->name('booking.extraGuest');

        Route::get('booking/details/print/{id}', [BookingController::class, 'printDetails'])->name('booking.details.print');
        Route::get('booking/delete/{id}', [BookingController::class, 'destroy'])->name('booking.delete');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    });

    Route::prefix('admin')->group(function () {
        Route::get('attendence', [AttendenceController::class, 'index'])->name('attendence.index');
        Route::get('attendence/view/{id}', [AttendenceController::class, 'view'])->name('attendence.view');
        Route::post('attendence/store', [AttendenceController::class, 'store'])->name('attendence.store');
        Route::post('attendence/update', [AttendenceController::class, 'update'])->name('attendence.update');
        Route::post('attendence/delete/{id}', [AttendenceController::class, 'delete'])->name('attendence.delete');
        Route::post('attendence/update-status', [AttendenceController::class, 'updateStatus'])->name('attendence.updateStatus');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('salary', [SalaryController::class, 'index'])->name('salary.index');
        Route::get('salary/create', [SalaryController::class, 'create'])->name('salary.create');
        Route::post('salary', [SalaryController::class, 'store'])->name('salary.store');
        Route::get('salary/{salary}', [SalaryController::class, 'show'])->name('salary.show');
        Route::post('salary/{salary}/delete', [SalaryController::class, 'delete'])->name('salary.delete');
        Route::get('get-absent-days', [SalaryController::class, 'getAbsentDays'])->name('salary.getAbsentDays');

        Route::get('dish_package', [DishPackageController::class, 'index'])->name('dish_package.index');
        Route::get('dish_package/create', [DishPackageController::class, 'create'])->name('dish_package.create');
        Route::post('dish_package', [DishPackageController::class, 'store'])->name('dish_package.store');
        Route::get('dish_package/{id}/edit', [DishPackageController::class, 'edit'])->name('dish_package.edit');
        Route::post('dish_package/{id}/update', [DishPackageController::class, 'update'])->name('dish_package.update');
        Route::post('dish_package/{id}/delete', [DishPackageController::class, 'destroy'])->name('dish_package.destroy');
    });

    // web.php
    Route::get('/admin/analysis/bookings', [AnalysisController::class, 'booking'])->name('admin.analysis.booking');
    Route::get('/admin/analysis/inventory', [AnalysisController::class, 'inventory'])->name('admin.analysis.inventory');
});

