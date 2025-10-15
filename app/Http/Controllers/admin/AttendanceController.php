<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Show monthly attendance grid
    public function index(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year  = (int) $request->input('year', now()->year);

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = (clone $start)->endOfMonth();

        $staffs = Staff::orderBy('name')->get();

        // Fetch attendance records for the month
        $records = Attendance::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn($r) => $r->staff_id . '_' . $r->date->toDateString());

        $days = collect();
        for ($d = 1; $d <= $start->daysInMonth; $d++) {
            $days->push(Carbon::create($year, $month, $d));
        }

        $title = 'Attendance Management';
        $heading = 'Monthly Attendance — ' . $start->format('F Y');
        $active = 'attendance';

        return view('admin.attendance.index', compact(
            'staffs',
            'records',
            'days',
            'month',
            'year',
            'title',
            'heading',
            'active'
        ));
    }

    // Bulk Save (AJAX)
    public function bulkSave(Request $request)
    {
        $data = $request->validate([
            'entries' => 'required|array',
            'entries.*.staff_id' => 'required|exists:staff,id',
            'entries.*.date' => 'required|date',
            'entries.*.status' => 'nullable|in:P,A,L,O',
            'entries.*.entry_time' => 'nullable|date_format:H:i',
            'entries.*.exit_time' => 'nullable|date_format:H:i',
            'entries.*.remarks' => 'nullable|string',
        ]);

        $saved = 0;
        foreach ($data['entries'] as $entry) {
            if (empty($entry['status'])) {
                Attendance::where('staff_id', $entry['staff_id'])
                    ->where('date', $entry['date'])
                    ->delete();
                continue;
            }

            Attendance::updateOrCreate(
                ['staff_id' => $entry['staff_id'], 'date' => $entry['date']],
                [
                    'status' => $entry['status'],
                    'entry_time' => $entry['entry_time'] ?? null,
                    'exit_time' => $entry['exit_time'] ?? null,
                    'remarks' => $entry['remarks'] ?? null,
                ]
            );
            $saved++;
        }

        return response()->json(['success' => true, 'message' => "$saved attendance entries saved."]);
    }

    // Delete attendance record
    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Attendance deleted successfully.');
    }
}
