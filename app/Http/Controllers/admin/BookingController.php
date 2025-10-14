<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('customer')->get();

        $data = [
            'heading' => 'Bookings Table',
            'title' => 'View Bookings',
            'active' => 'booking',
            'bookings' => $bookings,
        ];

        return view('admin.booking.index', $data);
    }

    public function create()
    {
        $customers = Customer::all();

        $data = [
            'heading' => 'Add New Booking',
            'title' => 'Create Booking',
            'active' => 'booking',
            'customers' => $customers,
        ];

        return view('admin.booking.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'event_type' => 'required|string',
            'booking_date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        $discounted = $request->total_amount - (($request->discount_percent / 100) * $request->total_amount);
        $remaining = $discounted - $request->advance_payment;

        Booking::create(array_merge($request->all(), [
            'remaining_amount' => $remaining,
            'total_amount' => $discounted,
        ]));

        return redirect()->route('booking.index')->with('success', 'Booking added successfully!');
    }

    public function edit($id)
    {
        $booking = Booking::find($id);
        $customers = Customer::all();

        $data = [
            'heading' => 'Edit Booking',
            'title' => 'Update Booking',
            'active' => 'booking',
            'booking' => $booking,
            'customers' => $customers,
        ];

        return view('admin.booking.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        $discounted = $request->total_amount - (($request->discount_percent / 100) * $request->total_amount);
        $remaining = $discounted - $request->advance_payment;

        $booking->update(array_merge($request->all(), [
            'remaining_amount' => $remaining,
            'total_amount' => $discounted,
        ]));

        return redirect()->route('booking.index')->with('success', 'Booking updated successfully!');
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully!');
    }
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
}
