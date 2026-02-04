<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Salary;
use App\Models\Staff;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    // Display salary records month-wise
    public function index(Request $request)
    {
        // ✅ Month/year from request (like attendance)
        $month = (int) $request->get('month', date('m'));
        $year  = (int) $request->get('year', date('Y'));

        $staffMembers = Staff::all();

        foreach ($staffMembers as $staff) {

            // Attendance for selected month/year
            $attendances = Attendence::where('staff_id', $staff->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();

            // Absent days
            $absent = $attendances->filter(function ($att) {
                return strtolower(trim($att->status)) === 'absent';
            })->count();

            // Salary calculations
            $basic = (float) ($staff->salary ?? 0);

            // per day deduction (assume 30 days)
            $deduction_per_day = $basic / 30;

            // ✅ total deduction for month
            $total_deduction = $absent * $deduction_per_day;

            // net salary
            $net_salary = $basic - $total_deduction;

            // ✅ upsert salary record for month/year
            Salary::updateOrCreate(
                [
                    'staff_id' => $staff->id,
                    'month'    => $month,
                    'year'     => $year,
                ],
                [
                    'basic'                => $basic,
                    'absent_days'          => $absent,
                    // ✅ store TOTAL deduction
                    'deduction_per_absent' => $total_deduction,
                    'net_salary'           => $net_salary,
                ]
            );
        }

        // ✅ show only selected month/year salaries
        $salaries = Salary::with('staff')
            ->where('month', $month)
            ->where('year', $year)
            ->latest()
            ->get();

        $title   = 'Salary Management';
        $heading = 'All Salary Records';
        $active  = 'salary';

        return view('admin.salary.index', compact(
            'salaries',
            'title',
            'heading',
            'active',
            'month',
            'year'
        ));
    }

    // Show form for generating new salary (if you have)
    public function create()
    {
        $staff   = Staff::all();
        $title   = 'Generate Salary';
        $heading = 'Create New Salary Record';
        $active  = 'salary';

        return view('admin.salary.create', compact('staff', 'title', 'heading', 'active'));
    }

    // Store manually (optional)
    public function store(Request $request)
    {
        $request->validate([
            'staff_id'        => 'required|exists:staff,id',
            'month'           => 'required|integer|min:1|max:12',
            'year'            => 'required|integer|min:2000|max:2100',
            'total_deduction' => 'required|numeric|min:0',
        ]);

        $staff = Staff::findOrFail($request->staff_id);

        $attendances = Attendence::where('staff_id', $staff->id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->get();

        $absent = $attendances->filter(function ($att) {
            return strtolower(trim($att->status)) === 'absent';
        })->count();

        $basic = (float) ($staff->salary ?? 0);
        $net_salary = $basic - (float) $request->total_deduction;

        Salary::create([
            'staff_id'              => $staff->id,
            'month'                 => $request->month,
            'year'                  => $request->year,
            'basic'                 => $basic,
            'absent_days'           => $absent,
            // ✅ store TOTAL deduction
            'deduction_per_absent'  => (float) $request->total_deduction,
            'net_salary'            => $net_salary,
        ]);

        return redirect()
            ->route('admin.salary.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', 'Salary generated successfully!');
    }

    // Salary slip
    public function show($id)
    {
        $salary  = Salary::with('staff')->findOrFail($id);
        $title   = 'Salary Slip';
        $heading = 'Salary Details';
        $active  = 'salary';

        return view('admin.salary.show', compact('salary', 'title', 'heading', 'active'));
    }

    // Delete salary record
    public function delete($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return redirect()->route('admin.salary.index')->with('success', 'Salary deleted successfully!');
    }

    // AJAX: Get absent days (optional)
    public function getAbsentDays(Request $request)
    {
        $absent = Attendence::where('staff_id', $request->staff_id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->whereRaw('LOWER(TRIM(status)) = ?', ['absent'])
            ->count();

        return response()->json(['absent_days' => $absent]);
    }
}
