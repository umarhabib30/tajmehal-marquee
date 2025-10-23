<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendenceController extends Controller
{
    // List attendance
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

        $data = [
            'staff'       => $staff,
            'attendances' => $attendances,
            'month'       => $month,
            'year'        => $year,
            'monthDays'   => $monthDays,
            'date'        => $date,
            'active'      => 'attendence',
            'heading'     => 'Attendance Management',
            'title'       => 'Staff Attendance',
        ];

        return view('admin.attendence.index', $data);
    }

    // Upsert attendance status (for checkboxes)
    public function updateStatus(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date'     => 'required|date',
            'status'   => 'required|in:present,absent,leave',
        ]);

        $staff_id = $request->staff_id;
        $date     = $request->date;
        $status   = $request->status;

        // Check 4-hour restriction if marking today
        if ($date === Carbon::today()->toDateString()) {
            $attendance = Attendence::where('staff_id', $staff_id)
                ->where('date', $date)
                ->first();

            if ($attendance && Carbon::parse($attendance->created_at)->addHours(4)->isPast()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You can no longer change today\'s attendance after 4 hours.',
                ]);
            }
        }

        Attendence::updateOrCreate(
            ['staff_id' => $staff_id, 'date' => $date],
            ['status' => $status]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Attendance updated successfully.',
        ]);
    }

    // Store new attendance (optional)
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date'     => 'required|date',
            'status'   => 'required|in:present,absent,leave',
        ]);

        Attendence::create([
            'staff_id' => $request->staff_id,
            'date'     => $request->date,
            'status'   => $request->status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Attendance created successfully.',
        ]);
    }

    // Delete attendance
    public function delete($id)
    {
        $attendance = Attendence::find($id);

        if (!$attendance) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Attendance not found.',
            ]);
        }

        $attendance->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Attendance deleted successfully.',
        ]);
    }

    // Mark leave via modal (optional)
    public function markLeave(Request $request)
    {
        $staff_id = $request->staff_id;
        $date     = $request->date ?? Carbon::today()->toDateString();
        $reason   = $request->reason ?? null;

        $attendance = Attendence::firstOrNew([
            'staff_id' => $staff_id,
            'date'     => $date,
        ]);

        $attendance->status = 'leave';
        if ($reason) $attendance->reason = $reason;
        $attendance->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Leave recorded successfully.',
        ]);
    }

    // View individual staff monthly report
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

        $data = [
            'staff'       => $staff,
            'attendances' => $attendances,
            'month'       => $month,
            'year'        => $year,
            'active'      => 'attendence',
            'heading'     => 'View Attendance Report',
            'title'       => 'Monthly Summary',
        ];

        return view('admin.attendence.view', $data);
    }
}
