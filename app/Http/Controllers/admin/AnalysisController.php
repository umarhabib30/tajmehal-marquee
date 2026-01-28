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

        $analysis = Booking::select(
            DB::raw('MONTH(event_date) as month'),
            DB::raw('COUNT(id) as total_bookings'),
            DB::raw('SUM(guests_count) as total_guests'),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('SUM(total_amount - remaining_amount) as total_paid'),
            DB::raw('SUM(remaining_amount) as total_pending')
        )
            ->whereYear('event_date', $year)
            ->where('status', 'Active')
            ->groupBy(DB::raw('MONTH(event_date)'))
            ->orderBy(DB::raw('MONTH(event_date)'))
            ->get()
            ->keyBy('month');

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
            $monthlyGuests[] = $analysis[$m]->total_guests ?? 0;
            $monthlyPaid[] = $analysis[$m]->total_paid ?? 0;
            $monthlyPending[] = $analysis[$m]->total_pending ?? 0;
        }

        $totalPaid = array_sum($monthlyPaid);
        $totalPending = array_sum($monthlyPending);

        if (($totalPaid + $totalPending) != array_sum($monthlySales)) {
            $totalPending = array_sum($monthlySales) - $totalPaid;
        }

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
         * Build per-item stats (ALL ITEMS)
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

        // ✅ Sort by "most used" (total_out) desc
        usort($rows, function ($a, $b) {
            return $b['total_out'] <=> $a['total_out'];
        });

        // ✅ TOP 10 ONLY for chart
        $topRows = array_slice($rows, 0, 10);

        /**
         * ✅ TABLE arrays (ALL ITEMS)
         */
        $labelsAll = [];
        $perItemPurchaseAll = [];
        $perItemQtyInAll = [];
        $perItemQtyOutAll = [];
        $perItemRemainAll = [];

        // Totals for category summary (ALL items totals)
        $totalQtyIn = 0;
        $totalQtyOut = 0;
        $totalPurchaseAmt = 0;

        foreach ($rows as $r) {
            $labelsAll[] = $r['name'];
            $perItemQtyInAll[] = $r['total_in'];
            $perItemQtyOutAll[] = $r['total_out'];
            $perItemPurchaseAll[] = $r['total_price'];
            $perItemRemainAll[] = $r['current_qty'];

            $totalQtyIn += $r['total_in'];
            $totalQtyOut += $r['total_out'];
            $totalPurchaseAmt += $r['total_price'];
        }

        /**
         * ✅ CHART arrays (TOP 10)
         */
        $labelsTop10 = [];
        $perItemQtyInTop10 = [];
        $perItemQtyOutTop10 = [];

        foreach ($topRows as $r) {
            $labelsTop10[] = $r['name'];
            $perItemQtyInTop10[] = $r['total_in'];
            $perItemQtyOutTop10[] = $r['total_out'];
        }

        $data = [
            'year' => now()->year,

            // ✅ TABLE (ALL)
            'chartMonths' => $labelsAll,
            'monthlySales' => $perItemPurchaseAll,
            'monthlyBookings' => $perItemQtyInAll,
            'monthlyPaid' => $perItemQtyOutAll,
            'monthlyPending' => $perItemRemainAll,

            // ✅ CHART (TOP 10)
            'chartMonthsTop10' => $labelsTop10,
            'monthlyBookingsTop10' => $perItemQtyInTop10,
            'monthlyPaidTop10' => $perItemQtyOutTop10,

            // ✅ Summary totals (ALL)
            'totalPaid' => $totalQtyIn,       // Total Quantity In (all items)
            'totalPending' => $totalQtyOut,   // Total Quantity Out (all items)
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
