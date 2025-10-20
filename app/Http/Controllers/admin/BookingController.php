<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Display all bookings
    public function index()
    {
        $bookings = Booking::with('customer')->latest()->get();
        $title = "Bookings List";
        $active = "bookings";
        $heading = "All Bookings";

        return view('admin.booking.index', compact('bookings', 'title', 'active', 'heading'));
    }

    // Show create form
    public function create()
    {
        $customers = Customer::all();
        $title = "Create Booking";
        $active = "bookings";
        $heading = "Add New Booking";

        return view('admin.booking.create', compact('customers', 'title', 'active', 'heading'));
    }

    // Store booking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'event_type'        => 'required|string|max:255',
            'guests_count'      => 'nullable|integer',
            'booking_date'      => 'required|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date',
            'booking_time'      => 'nullable|string|max:255',
            'time_slot'         => 'nullable|string|max:255',
            'hall_name'         => 'nullable|string|max:255',
            'decoration_type'   => 'nullable|string|max:255',
            'menu_package'      => 'nullable|string|max:255',
            'total_amount'      => 'required|numeric',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'advance_payment'   => 'nullable|numeric|min:0',
            'payment_status'    => 'nullable|string|max:255',
            'status'            => 'nullable|string|max:255',
            'special_request'   => 'nullable|string',
            'customer_signature' => 'nullable|string',
        ]);

        $discount = $request->discount_percent ?? 0;
        $discounted = $request->total_amount - ($request->total_amount * ($discount / 100));
        $remaining = $discounted - ($request->advance_payment ?? 0);

        $booking = Booking::create(array_merge($validated, [
            'total_amount' => $discounted,
            'remaining_amount' => $remaining,
        ]));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully!',
                'booking_id' => $booking->id
            ]);
        }

        return redirect()->route('admin.booking.index')->with('success', 'Booking created successfully!');
    }

    // Show customer details for AJAX
    public function getCustomer($id)
    {
        $customer = Customer::find($id);
        return response()->json([
            'email' => $customer->email ?? '',
            'phone' => $customer->phone ?? ''
        ]);
    }

    // Edit booking
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $customers = Customer::all();
        $title = "Edit Booking";
        $active = "bookings";
        $heading = "Update Booking";

        return view('admin.booking.edit', compact('booking', 'customers', 'title', 'active', 'heading'));
    }

    // Update booking
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'event_type'        => 'required|string|max:255',
            'guests_count'      => 'nullable|integer',
            'booking_date'      => 'required|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date',
            'booking_time'      => 'nullable|string|max:255',
            'time_slot'         => 'nullable|string|max:255',
            'hall_name'         => 'nullable|string|max:255',
            'decoration_type'   => 'nullable|string|max:255',
            'menu_package'      => 'nullable|string|max:255',
            'total_amount'      => 'required|numeric',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'advance_payment'   => 'nullable|numeric|min:0',
            'payment_status'    => 'nullable|string|max:255',
            'status'            => 'nullable|string|max:255',
            'special_request'   => 'nullable|string',
            'customer_signature' => 'nullable|string',
        ]);

        $discount = $request->discount_percent ?? 0;
        $discounted = $request->total_amount - ($request->total_amount * ($discount / 100));
        $remaining = $discounted - ($request->advance_payment ?? 0);

        $booking->update(array_merge($validated, [
            'total_amount' => $discounted,
            'remaining_amount' => $remaining,
        ]));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully!',
                'booking_id' => $booking->id
            ]);
        }

        return redirect()->route('admin.booking.index')->with('success', 'Booking updated successfully!');
    }

    // Delete booking
    public function destroy(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully!',
            ]);
        }

        return redirect()->route('admin.booking.index')->with('success', 'Booking deleted successfully!');
    }

    // Show booking details
    public function show($id)
    {
        $booking = Booking::with('customer')->findOrFail($id);
        $title = "Booking Details";
        $active = "bookings";
        $heading = "View Booking";

        return view('admin.booking.show', compact('booking', 'title', 'active', 'heading'));
    }
}
