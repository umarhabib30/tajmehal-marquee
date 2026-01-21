<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Dish;
use App\Models\DishPackage;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function create($id = null)
    {
        $data = [
            'customers' => Customer::all(),
            'dishPackages' => DishPackage::with('dishes')->get(),  // loads dishes
            'dishes' => Dish::all(),
            'heading' => 'Add Booking',
            'active' => 'booking',
            'title' => 'Create Booking',
            'customer' => $id ? Customer::find($id) : null,
        ];

        return view('admin.bookings.create', $data);
    }

    // Store booking

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'customer_phone' => 'nullable|string|max:255',
            'customer_idcard' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:255',
            'event_type' => 'required|string|max:255',
            'hall_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'time_slot' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'package_id' => 'nullable|exists:dish_packages,id',
            'dishes' => 'nullable|string',  // comes as JSON string
            'decorations' => 'nullable|array',
            'decore_price' => 'nullable|numeric',
            'guests_count' => 'required|integer|min:1',
            'per_head_price' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'customer_signature' => 'nullable|string',
            'manager_signature' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:Active,Cancelled',

        ]);

        // 🧠 Convert JSON/Array fields
        $validated['dishes'] = $request->filled('dishes') ? $request->dishes : json_encode([]);
        $validated['decorations'] = $request->filled('decorations') ? json_encode($request->decorations) : json_encode([]);
        $validated['booking_date'] = Carbon::now();
        $validated['decore_price'] = $request->decore_price ?? 0;
        $validated['tax_amount'] = $request->tax_amount ?? 0;
        $validated['advance_payment'] = $request->advance_payment ?? 0;
        $validated['status'] = $request->status ?? 'Active';

        // ✅ DUPLICATE CHECK
        $duplicate = Booking::whereDate('event_date', $request->event_date)
            ->where('hall_name', $request->hall_name)
            ->where('time_slot', $request->time_slot)
            ->first();

        if ($duplicate) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'This hall is already booked for this date and time slot.');
        }


        // ✅ Create the booking
        $booking = Booking::create($validated);

        // ✅ Record initial payment (advance)
        if ($request->filled('advance_payment') && $request->advance_payment > 0) {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $request->advance_payment,
                'payment_date' => Carbon::now(),
                'payment_method' => $request->payment_method ?? 'Not Specified',
            ]);
        }

        return redirect()->route('admin.booking.details.print',$booking->id)->with('success', 'Booking created successfully');
    }

    // Show single booking

    public function show($id)
    {
        $booking = Booking::with(['customer', 'dishPackage.dishes', 'payments'])->findOrFail($id);

        // Decode selected dishes and fetch their details
        $dishes = [];
        if (!empty($booking->dishes)) {
            $dishIds = json_decode($booking->dishes, true);
            if (is_array($dishIds) && count($dishIds) > 0) {
                $dishes = \App\Models\Dish::whereIn('id', $dishIds)->get();
            }
        }

        return view('admin.bookings.show', [
            'booking' => $booking,
            'heading' => 'Booking Details',
            'active' => 'booking',
            'title' => 'Booking Details',
            'dishes' => $dishes,
        ]);
    }

    // Edit booking form

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $customers = Customer::all();
        $dishPackages = DishPackage::with('dishes')->get();
        $dishes = Dish::all();

        // Decode JSON fields
        $booking->decorations = json_decode($booking->decorations, true) ?? [];
        $booking->dishes = json_decode($booking->dishes, true) ?? [];

        $data = [
            'booking' => $booking,
            'customers' => $customers,
            'dishPackages' => $dishPackages,
            'dishes' => $dishes,
            'heading' => 'Bookings',
            'active' => 'booking',
            'title' => 'Bookings List'
        ];

        return view('admin.bookings.edit', $data);
    }

    // Update booking

    public function update(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'customer_phone' => 'nullable|string|max:255',
            'customer_idcard' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:255',
            'event_type' => 'required|string|max:255',
            'hall_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'time_slot' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'package_id' => 'nullable|exists:dish_packages,id',
            'dishes' => 'nullable|string',
            'decorations' => 'nullable|array',
            'decore_price' => 'nullable|numeric',
            'guests_count' => 'required|integer|min:1',
            'per_head_price' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'customer_signature' => 'nullable|string',
            'manager_signature' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:Active,Cancelled',

        ]);

        // ✅ Duplicate validation (same hall + same date + same time slot not allowed)
        $duplicate = Booking::whereDate('event_date', $request->event_date)
            ->where('hall_name', $request->hall_name)
            ->where('time_slot', $request->time_slot)
            ->where('id', '!=', $request->id) // ✅ ignore current record
            ->first();

        if ($duplicate) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'This hall is already booked for this date and time slot.');
        }

        // 🧠 Convert JSON fields
        $validated['dishes'] = $request->filled('dishes') ? $request->dishes : json_encode([]);
        $validated['decorations'] = $request->filled('decorations')
            ? json_encode($request->decorations)
            : json_encode([]);

        $validated['decore_price'] = $request->decore_price ?? 0;
        $validated['tax_amount'] = $request->tax_amount ?? 0;
        $validated['advance_payment'] = $request->advance_payment ?? 0;

        $booking = Booking::findOrFail($request->id);
        $booking->update($validated);

        return redirect()->route('admin.booking.index')->with('success', 'Booking updated successfully');
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

    public function addPaymentPage($id)
    {
        $booking = Booking::findOrFail($id);
        $data = [
            'booking' => $booking,
            'heading' => 'Bookings',
            'active' => 'booking',
            'title' => 'Bookings Payment'
        ];
        return view('admin.bookings.payment', $data);
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
        ]);

        $booking = Booking::find($request->booking_id);
        $booking->remaining_amount -= $request->amount;
        $booking->save();

        Payment::create([
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

    public function extraGuest(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'extra_guests' => 'required|integer|min:1',
            'per_head_price' => 'required|numeric|min:0',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $additionalAmount = $request->extra_guests * $request->per_head_price;

        $booking->guests_count += $request->extra_guests;
        $booking->total_amount += $additionalAmount;
        $booking->remaining_amount += $additionalAmount;
        $booking->extra_guests = ($booking->extra_guests ?? 0) + $request->extra_guests;
        $booking->save();

        return redirect()->back()->with('success', 'Extra guests added successfully.');
    }

    public function invoice($id)
    {
        $booking = Booking::with(['customer', 'dishPackage.dishes', 'payments'])->findOrFail($id);

        // Decode custom dish selection
        $dishes = [];
        if (!empty($booking->dishes)) {
            $dishIds = json_decode($booking->dishes, true);
            if (is_array($dishIds) && count($dishIds) > 0) {
                $dishes = \App\Models\Dish::whereIn('id', $dishIds)->get();
            }
        }

        // Decode decorations safely
        $decorations = is_array($booking->decorations)
            ? $booking->decorations
            : (json_decode($booking->decorations, true) ?? []);

        // Calculate total paid
        $totalPaid = $booking->payments->sum('amount');

        return view('admin.bookings.invoice', [
            'booking' => $booking,
            'dishes' => $dishes,
            'decorations' => $decorations,
            'totalPaid' => $totalPaid,
            'heading' => 'Booking Invoice',
            'title' => 'Bookings Invoice',
            'active' => 'booking',
        ]);
    }

    public function printDetails($id)
    {
        $booking = Booking::with(['customer', 'dishPackage.dishes', 'payments'])->findOrFail($id);

        // Decode custom dish selection
        $dishes = [];
        if (!empty($booking->dishes)) {
            $dishIds = json_decode($booking->dishes, true);
            if (is_array($dishIds) && count($dishIds) > 0) {
                $dishes = \App\Models\Dish::whereIn('id', $dishIds)->get();
            }
        }

        // Decode decorations safely
        $decorations = is_array($booking->decorations)
            ? $booking->decorations
            : (json_decode($booking->decorations, true) ?? []);

        // Calculate total paid
        $totalPaid = $booking->payments->sum('amount');

        return view('admin.bookings.invoice_backup', [
            'booking' => $booking,
            'dishes' => $dishes,
            'decorations' => $decorations,
            'totalPaid' => $totalPaid,
            'heading' => 'Booking Invoice',
            'title' => 'Bookings Invoice',
            'active' => 'booking',
        ]);
    }
    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking.'
            ]);
        }
    }
}
