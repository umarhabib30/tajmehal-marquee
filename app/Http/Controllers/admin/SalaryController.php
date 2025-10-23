<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\Staff;
use App\Models\Attendence;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    // Display all salary records
    public function index()
    {
        $salaries = Salary::with('staff')->latest()->get();
        $title = 'Salary Management';
        $heading = 'All Salary Records';
        $active = 'salary';

        return view('admin.salary.index', compact('salaries', 'title', 'heading', 'active'));
    }

    // Show form for generating new salary
    public function create()
    {
        $staff = Staff::all();
        $title = 'Generate Salary';
        $heading = 'Create New Salary Record';
        $active = 'salary';

        return view('admin.salary.create', compact('staff', 'title', 'heading', 'active'));
    }

    // Store newly calculated salary
    // Store newly calculated salary
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'total_deduction' => 'required|numeric|min:0', // now only total deduction
        ]);

        $staff = Staff::findOrFail($request->staff_id);

        // Fetch attendance for the selected month/year
        $attendances = Attendence::where('staff_id', $staff->id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->get();

        // Count absent days for reference
        $absent = $attendances->filter(function ($att) {
            return strtolower(trim($att->status)) === 'absent';
        })->count();

        $basic = $staff->salary ?? 0;
        $net_salary = $basic - $request->total_deduction;

        // Save salary record
        Salary::create([
            'staff_id' => $staff->id,
            'month' => $request->month,
            'year' => $request->year,
            'basic' => $basic,
            'absent_days' => $absent,
            'deduction_per_absent' => $request->total_deduction, // store total deduction
            'net_salary' => $net_salary,
        ]);

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary generated successfully!');
    }

    // Show salary slip
    public function show($id)
    {
        $salary = Salary::with('staff')->findOrFail($id);
        $title = 'Salary Slip';
        $heading = 'Salary Details';
        $active = 'salary';

        return view('admin.salary.show', compact('salary', 'title', 'heading', 'active'));
    }

    // Delete salary record
    public function delete($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return redirect()->route('admin.salary.index')->with('success', 'Salary deleted successfully!');
    }

    // AJAX: Get total absent days
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
