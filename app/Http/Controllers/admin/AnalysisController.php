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

        /**
         * Build per-item stats first, then sort and pick top 10
         * "Most using inventory" = highest total_out (most consumed)
         */
        $rows = [];

        foreach ($items as $item) {
            $stocks = InventoryStock::where('inventory_id', $item->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select(
                    DB::raw('COALESCE(SUM(quantity_in),0) as total_in'),
                    DB::raw('COALESCE(SUM(quantity_out),0) as total_out'),
                    DB::raw('COALESCE(SUM(quantity_in * price_per_unit),0) as total_price')
                )
                ->first();

            $totalIn = (float) ($stocks->total_in ?? 0);
            $totalOut = (float) ($stocks->total_out ?? 0);
            $totalPrice = (float) ($stocks->total_price ?? 0);
            $currentQty = (float) ($item->quantity ?? 0);

            $rows[] = [
                'name' => $item->name,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'total_price' => $totalPrice,
                'current_qty' => $currentQty,
            ];
        }

        // ✅ Sort by "most used" (total_out) desc, then take top 10
        usort($rows, function ($a, $b) {
            return $b['total_out'] <=> $a['total_out'];
        });

        $topRows = array_slice($rows, 0, 10);

        // Prepare arrays for chart and summary (ONLY top 10)
        $labels = [];
        $perItemPurchase = [];
        $perItemQtyIn = [];
        $perItemQtyOut = [];
        $perItemRemain = [];

        $totalQtyIn = 0;
        $totalQtyOut = 0;
        $totalPurchaseAmt = 0;

        foreach ($topRows as $r) {
            $labels[] = $r['name'];
            $perItemQtyIn[] = $r['total_in'];
            $perItemQtyOut[] = $r['total_out'];
            $perItemPurchase[] = $r['total_price'];
            $perItemRemain[] = $r['current_qty'];

            $totalQtyIn += $r['total_in'];
            $totalQtyOut += $r['total_out'];
            $totalPurchaseAmt += $r['total_price'];
        }

        $data = [
            'year' => now()->year,
            'chartMonths' => $labels,          // item names (top 10)
            'monthlySales' => $perItemPurchase, // total purchase per item (top 10)
            'monthlyBookings' => $perItemQtyIn, // total quantity in (top 10)
            'monthlyPaid' => $perItemQtyOut,    // total quantity out (top 10)
            'monthlyPending' => $perItemRemain, // remaining quantity (top 10)

            // Donut / totals (top 10 totals)
            'totalPaid' => $totalQtyIn,
            'totalPending' => $totalQtyOut,

            'totalPurchaseAmt' => $totalPurchaseAmt,
            'category' => $category,
            'categories' => $categories,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'heading' => 'Inventory Analysis',
            'active' => 'analysis',
            'title' => 'Analysis Dashboard',
        ];

        return view('admin.analysis.inventory', $data);
    }
}
