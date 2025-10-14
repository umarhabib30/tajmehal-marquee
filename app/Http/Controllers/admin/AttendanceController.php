<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('staff')->orderBy('attendance_date', 'desc')->get();
        $title = 'Attendance Management';
        $heading = 'All Attendance Records';
        $active = 'attendance';
        return view('admin.attendance.index', compact('attendances', 'title', 'heading', 'active'));
    }

    public function create()
    {
        $staffs = Staff::all();
        $title = 'Attendance Management';
        $heading = 'Add Attendance Record';
        $active = 'attendance';
        return view('admin.attendance.create', compact('staffs', 'title', 'heading', 'active'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'attendance_date' => 'required|date',
            'entry_time' => 'nullable|date_format:H:i',
            'exit_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:Present,Absent,Half Leave,Leave',
            'remarks' => 'nullable|string',
        ]);

        Attendance::create($request->all());
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance added successfully.');
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $staffs = Staff::all();
        $title = 'Attendance Management';
        $heading = 'Edit Attendance Record';
        $active = 'attendance';
        return view('admin.attendance.edit', compact('attendance', 'staffs', 'title', 'heading', 'active'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'attendance_date' => 'required|date',
            'entry_time' => 'nullable|date_format:H:i',
            'exit_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:Present,Absent,Half Leave,Leave',
            'remarks' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }
}
