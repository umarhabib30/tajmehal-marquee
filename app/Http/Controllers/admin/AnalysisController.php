<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Inventory;
use App\Models\InventoryStock;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function booking(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Combine bookings and payments by booking_id to correctly align data
        $analysis = Booking::select(
            DB::raw('MONTH(event_date) as month'),
            DB::raw('COUNT(id) as total_bookings'),
            DB::raw('SUM(guests_count) as total_guests'), // ✅ NEW
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('SUM(total_amount - remaining_amount) as total_paid'),
            DB::raw('SUM(remaining_amount) as total_pending')
        )
            ->whereYear('event_date', $year)
            ->where('status', 'Active') // ✅ only active
            ->groupBy(DB::raw('MONTH(event_date)'))
            ->orderBy(DB::raw('MONTH(event_date)'))
            ->get()
            ->keyBy('month');



        // Prepare chart data
        $chartMonths = [];
        $monthlySales = [];
        $monthlyBookings = [];
        $monthlyPaid = [];
        $monthlyPending = [];

        $monthlyGuests = [];

        foreach (range(1, 12) as $m) {
            $chartMonths[] = Carbon::create()->month($m)->format('M');
            $monthlySales[] = $analysis[$m]->total_sales ?? 0;
            $monthlyBookings[] = $analysis[$m]->total_bookings ?? 0;
            $monthlyGuests[] = $analysis[$m]->total_guests ?? 0; // ✅ NEW
            $monthlyPaid[] = $analysis[$m]->total_paid ?? 0;
            $monthlyPending[] = $analysis[$m]->total_pending ?? 0;
        }

        // Totals for donut chart (ensures consistency)
        $totalPaid = array_sum($monthlyPaid);
        $totalPending = array_sum($monthlyPending);

        // Ensure pending recalculated correctly if rounding or missing payments exist
        if (($totalPaid + $totalPending) != array_sum($monthlySales)) {
            $totalPending = array_sum($monthlySales) - $totalPaid;
        }

        // Return data to view
        $data = [
            'monthlyGuests' => $monthlyGuests,
            'totalGuests' => array_sum($monthlyGuests),

            'year' => $year,
            'chartMonths' => $chartMonths,
            'monthlySales' => $monthlySales,
            'monthlyBookings' => $monthlyBookings,
            'monthlyPaid' => $monthlyPaid,
            'monthlyPending' => $monthlyPending,
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
            'heading' => 'Sales & Booking Analysis',
            'active' => 'analysis',
            'title' => 'Analysis Dashboard',
        ];

        return view('admin.analysis.booking', $data);
    }

    public function inventory(Request $request)
    {
        // Default category = Food
        $category = $request->get('category', 'Food');
        $categories = ['Food', 'Electronics', 'Furniture', 'Decoration', 'Crockery'];

        // Date range: from 1st of last month to today
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now();

        // Get inventory items filtered by category
        $items = Inventory::where('category', $category)->get();

        // Prepare arrays for chart and summary
        $labels = [];
        $perItemPurchase = [];  // total purchase amount (price * quantity_in)
        $perItemQtyIn = [];
        $perItemQtyOut = [];
        $perItemRemain = [];

        $totalQtyIn = 0;
        $totalQtyOut = 0;
        $totalPurchaseAmt = 0;

        foreach ($items as $item) {
            $stocks = InventoryStock::where('inventory_id', $item->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select(
                    DB::raw('SUM(quantity_in) as total_in'),
                    DB::raw('SUM(quantity_out) as total_out'),
                    DB::raw('SUM(quantity_in * price_per_unit) as total_price')
                )
                ->first();

            $totalIn = $stocks->total_in ?? 0;
            $totalOut = $stocks->total_out ?? 0;
            $totalPrice = $stocks->total_price ?? 0;
            $currentQty = $item->quantity ?? 0;

            $labels[] = $item->name;
            $perItemQtyIn[] = $totalIn;
            $perItemQtyOut[] = $totalOut;
            $perItemPurchase[] = $totalPrice;
            $perItemRemain[] = $currentQty;

            $totalQtyIn += $totalIn;
            $totalQtyOut += $totalOut;
            $totalPurchaseAmt += $totalPrice;
        }

        // Prepare data array for the view
        $data = [
            'year' => now()->year,
            'chartMonths' => $labels,  // item names
            'monthlySales' => $perItemPurchase,  // total purchase per item
            'monthlyBookings' => $perItemQtyIn,  // total quantity in
            'monthlyPaid' => $perItemQtyOut,  // total quantity out
            'monthlyPending' => $perItemRemain,  // remaining quantity in inventory
            'totalPaid' => $totalQtyIn,  // total quantity in (for donut)
            'totalPending' => $totalQtyOut,  // total quantity out (for donut)
            'totalPurchaseAmt' => $totalPurchaseAmt,
            'category' => $category,
            'categories' => $categories,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'heading' => 'Inventory Analysis',
            'active' => 'analysis',
            'title' => 'Analysis Dashboard',
        ];

        // Reuse your booking analysis blade (or change to a dedicated inventory view)
        return view('admin.analysis.inventory', $data);
    }
}
