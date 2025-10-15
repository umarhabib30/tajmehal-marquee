<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Display list of bookings (index)
    public function index()
    {
        $bookings = Booking::with('customer')->get();

        return view('admin.booking.index', [
            'heading' => 'Bookings Table',
            'title' => 'View Bookings',
            'active' => 'booking',
            'bookings' => $bookings,
        ]);
    }

    // Show create form
    public function create()
    {
        $customers = Customer::all();

        return view('admin.booking.create', [
            'heading' => 'Add New Booking',
            'title' => 'Create Booking',
            'active' => 'booking',
            'customers' => $customers,
        ]);
    }

    // Store booking
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'event_type' => 'required|string',
            'guests_count' => 'nullable|numeric',
            'booking_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'booking_time' => 'nullable',
            'hall_name' => 'required|string',
            'total_amount' => 'required|numeric',
            'discount_percent' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric',
        ]);

        // ✅ Hall uniqueness check
        $exists = Booking::where('hall_name', $request->hall_name)
            ->where('booking_date', $request->booking_date)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()
                ->with('error', 'This hall is already booked for the selected date.');
        }

        $discounted = $request->total_amount - (($request->discount_percent ?? 0) / 100 * $request->total_amount);
        $remaining = $discounted - ($request->advance_payment ?? 0);

        Booking::create(array_merge($request->all(), [
            'total_amount' => $discounted,
            'remaining_amount' => $remaining,
        ]));

        return redirect()->route('booking.index')->with('success', 'Booking added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $customers = Customer::all();

        return view('admin.booking.edit', [
            'heading' => 'Edit Booking',
            'title' => 'Update Booking',
            'active' => 'booking',
            'booking' => $booking,
            'customers' => $customers,
        ]);
    }

    // Update booking
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'customer_id' => 'required',
            'event_type' => 'required|string',
            'guests_count' => 'nullable|numeric',
            'booking_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'booking_time' => 'nullable',
            'hall_name' => 'required|string',
            'total_amount' => 'required|numeric',
            'discount_percent' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric',
        ]);

        // ✅ Hall uniqueness check for update
        $exists = Booking::where('hall_name', $request->hall_name)
            ->where('booking_date', $request->booking_date)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()
                ->with('error', 'This hall is already booked for the selected date.');
        }

        $discounted = $request->total_amount - (($request->discount_percent ?? 0) / 100 * $request->total_amount);
        $remaining = $discounted - ($request->advance_payment ?? 0);

        $booking->update(array_merge($request->all(), [
            'total_amount' => $discounted,
            'remaining_amount' => $remaining,
        ]));

        return redirect()->route('booking.index')->with('success', 'Booking updated successfully!');
    }

    // Delete booking
    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully!');
    }


    // AJAX: Get customer email & phone
    public function getCustomer($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            return response()->json([
                'email' => $customer->email,
                'phone' => $customer->phone,
            ]);
        }

        return response()->json(['error' => 'Customer not found'], 404);
    }

    // Show booking details (full view)
    public function show($id)
    {
        $booking = Booking::with('customer')->findOrFail($id);

        return view('admin.booking.show', [
            'heading' => 'Booking Details',
            'title' => 'Booking Detail',
            'active' => 'booking',
            'booking' => $booking,
        ]);
    }
}
