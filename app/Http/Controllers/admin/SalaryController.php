<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\Staff;
use App\Models\Attendence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Display all salary records.
     */
    public function index()
    {
        $salaries = Salary::with('staff')->latest()->get();
        $title = 'Salary Management';
        $heading = 'All Salary Records';
        $active = 'salary';

        return view('admin.salary.index', compact('salaries', 'title', 'heading', 'active'));
    }

    /**
     * Show the form for generating a new salary.
     */
    public function create()
    {
        $staff = Staff::all();
        $title = 'Generate Salary';
        $heading = 'Create New Salary Record';
        $active = 'salary';

        return view('admin.salary.create', compact('staff', 'title', 'heading', 'active'));
    }

    /**
     * Store a newly calculated salary record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'overtime_rate' => 'required|numeric|min:0',
            'deduction_per_absent' => 'required|numeric|min:0',
            'deduction_per_leave' => 'required|numeric|min:0',
        ]);

        $staff = Staff::findOrFail($request->staff_id);

        // Fetch attendance data for the selected month and year
        $attendances = Attendence::where('staff_id', $staff->id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->get();

        // Calculate attendance stats
        $overtime = $attendances->where('status', 'Overtime')->count();
        $absent = $attendances->where('status', 'Absent')->count();
        $leave = $attendances->where('status', 'Leave')->count();

        // Basic salary
        $basic = $staff->salary ?? 0;

        // Final salary calculation
        $net_salary = $basic
            + ($overtime * $request->overtime_rate)
            - ($absent * $request->deduction_per_absent)
            - ($leave * $request->deduction_per_leave);

        // Save record
        Salary::create([
            'staff_id' => $staff->id,
            'month' => $request->month,
            'year' => $request->year,
            'basic' => $basic,
            'overtime_hours' => $overtime,
            'absent_days' => $absent,
            'leave_days' => $leave,
            'overtime_rate' => $request->overtime_rate,
            'deduction_per_absent' => $request->deduction_per_absent,
            'deduction_per_leave' => $request->deduction_per_leave,
            'net_salary' => $net_salary,
        ]);

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary generated successfully!');
    }

    /**
     * Display a specific salary slip.
     */
    public function show($id)
    {
        $salary = Salary::with('staff')->findOrFail($id);
        $title = 'Salary Slip';
        $heading = 'Salary Details';
        $active = 'salary';

        return view('admin.salary.show', compact('salary', 'title', 'heading', 'active'));
    }
}
