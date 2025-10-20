<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendenceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year  = $request->get('year', Carbon::now()->year);
        $date  = $request->get('date', Carbon::today()->toDateString());

        $staff = Staff::all();
        $monthDays = Carbon::createFromDate($year, $month)->daysInMonth;
        $attendances = Attendence::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        return view('admin.attendence.index', [
            'staff' => $staff,
            'attendances' => $attendances,
            'month' => $month,
            'year' => $year,
            'monthDays' => $monthDays,
            'date' => $date,
            'active' => 'attendence',
            'heading' => 'Attendance Management',
            'title' => 'Staff Attendance',
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'entry_time' => 'nullable|date_format:H:i:s',
            'exit_time'  => 'nullable|date_format:H:i:s',
            'status'     => 'required|in:present,leave,absent',
        ]);

        $attendance = Attendence::findOrFail($id);
        $attendance->entry_time = $request->entry_time ?: null;
        $attendance->exit_time  = $request->exit_time ?: null;
        $attendance->status     = $request->status;
        $attendance->save();

        $worked = $attendance->entry_time && $attendance->exit_time
            ? Carbon::parse($attendance->entry_time)
            ->diffInMinutes(Carbon::parse($attendance->exit_time)) / 60
            : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance updated successfully!',
            'worked' => $worked
        ]);
    }

    public function store(Request $request)
    {
        $attendance = new Attendence();
        $attendance->staff_id = $request->staff_id;
        $attendance->date = $request->date;
        $attendance->entry_time = $request->entry_time;
        $attendance->exit_time = $request->exit_time;
        $attendance->status = $request->status;
        $attendance->save();

        return response()->json(['status' => 'success', 'message' => 'Attendance created!']);
    }

    public function delete(Request $request, $id)
    {
        $attendance = Attendence::find($id);
        if (!$attendance) {
            return response()->json(['status' => 'error', 'message' => 'Attendance not found']);
        }
        $attendance->delete();
        return response()->json(['status' => 'success', 'message' => 'Attendance deleted!']);
    }

    public function markEntry(Request $request)
    {
        $attendance = Attendence::firstOrNew([
            'staff_id' => $request->staff_id,
            'date' => $request->date ?? Carbon::today()->toDateString(),
        ]);

        $attendance->entry_time = $request->time ?? Carbon::now()->format('H:i:s');
        $attendance->status = 'present';
        $attendance->save();

        return response()->json(['status' => 'success', 'message' => 'Entry time saved']);
    }

    public function markExit(Request $request)
    {
        $attendance = Attendence::firstOrNew([
            'staff_id' => $request->staff_id,
            'date' => $request->date ?? Carbon::today()->toDateString(),
        ]);

        $attendance->exit_time = $request->time ?? Carbon::now()->format('H:i:s');
        $attendance->status = 'present';
        $attendance->save();

        return response()->json(['status' => 'success', 'message' => 'Exit time saved']);
    }

    public function markLeave(Request $request)
{
    $staff_id = $request->staff_id;
    $date = $request->date ?? Carbon::today()->toDateString();
    $reason = $request->reason ?? null;

    // Keep existing entry/exit, just update status
    $attendance = Attendence::firstOrNew([
        'staff_id' => $staff_id,
        'date' => $date
    ]);

    $attendance->status = 'leave';
    if ($reason) $attendance->reason = $reason;
    $attendance->save();

    return response()->json(['status' => 'success', 'message' => 'Leave recorded']);
}

    public function view($id, Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year  = $request->get('year', Carbon::now()->year);

        $staff = Staff::findOrFail($id);
        $attendances = Attendence::where('staff_id', $id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        return view('admin.attendence.view', [
            'staff' => $staff,
            'attendances' => $attendances,
            'month' => $month,
            'year' => $year,
            'active' => 'attendence',
            'heading' => 'View Attendance Report',
            'title' => 'Monthly Summary',
        ]);
    }
}
