<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\DishPackage;
use App\Models\Dish;

class BookingController extends Controller
{
    // Index page
    public function index(Request $request)
    {
        $bookings = Booking::with('customer')->orderBy('booking_date', 'desc')->get();

        $data = [
            'bookings' => $bookings,
            'heading' => 'Bookings',
            'active' => 'booking',
            'title' => 'Bookings List'
        ];

        return view('admin.bookings.index', $data);
    }

    // Show create form
    public function create()
    {
        $data = [
            'customers' => Customer::all(),
            'dishPackages' => DishPackage::all(),
            'dishes' => Dish::all(),
            'heading' => 'Create Booking',
            'active' => 'booking',
            'title' => 'Create Booking'
        ];

        return view('admin.bookings.create', $data);
    }

    // Store booking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'event_type' => 'required',
            'hall_name' => 'required',
            'booking_date' => 'required|date',
            'time_slot' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'dish_package_id' => 'required|exists:dish_packages,id',
            'guests_count' => 'required|integer',
            'price_per_head' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
        ]);

        Booking::create($validated + [
            'decoration' => $request->decoration ?? null,
            'decoration_amount' => $request->decoration_amount ?? 0,
            'tax' => $request->tax ?? 0,
            'customer_signature' => $request->customer_signature ?? null,
            'manager_signature' => $request->manager_signature ?? null,
            'notes' => $request->notes ?? null
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully');
    }

    // Show single booking
    public function show($id)
    {
        $booking = Booking::with('customer', 'dishPackage.dishes')->findOrFail($id);

        $data = [
            'booking' => $booking,
            'heading' => 'Booking Details',
            'active' => 'booking',
            'title' => 'Booking Details'
        ];

        return view('admin.bookings.show', $data);
    }

    // Edit booking form
    public function edit($id)
    {
        $data = [
            'booking' => Booking::findOrFail($id),
            'customers' => Customer::all(),
            'dishPackages' => DishPackage::all(),
            'dishes' => Dish::all(),
            'heading' => 'Edit Booking',
            'active' => 'booking',
            'title' => 'Edit Booking'
        ];

        return view('admin.bookings.edit', $data);
    }

    // Update booking
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'event_type' => 'required',
            'hall_name' => 'required',
            'booking_date' => 'required|date',
            'time_slot' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'dish_package_id' => 'required|exists:dish_packages,id',
            'guests_count' => 'required|integer',
            'price_per_head' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
        ]);

        $booking->update($validated + [
            'decoration' => $request->decoration ?? null,
            'decoration_amount' => $request->decoration_amount ?? 0,
            'tax' => $request->tax ?? 0,
            'customer_signature' => $request->customer_signature ?? null,
            'manager_signature' => $request->manager_signature ?? null,
            'notes' => $request->notes ?? null
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully');
    }

    // Delete booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully');
    }

    // AJAX - get customer details
    public function getCustomer($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'phone' => $customer->phone,
            'email' => $customer->email,
            'address' => $customer->address
        ]);
    }

    // AJAX - get dishes for selected package
    public function getPackageDishes($package_id)
    {
        $package = DishPackage::with('dishes')->findOrFail($package_id);

        return response()->json($package->dishes);
    }
}
