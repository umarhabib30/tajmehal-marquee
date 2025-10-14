<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    // Show calendar page
    public function index()
    {
        $title = 'Calendar';
        $heading = 'Manage Calendar Events';
        $active = 'calendar';

        return view('admin.calendar.index', compact('title', 'heading', 'active'));
    }

    // Return events for FullCalendar (JSON)
    public function getEvents()
    {
        $events = Event::all();

        $formatted = $events->map(function ($event) {
            $isPassed = Carbon::parse($event->end)->isPast();
            $status = $isPassed ? 'passed' : 'upcoming';

            return [
                'id' => $event->id,
                'title' => $event->title . ($isPassed ? ' (Passed)' : ''),
                'start' => $event->start,
                'end' => $event->end,
                'color' => $isPassed ? '#9e9e9e' : ($event->color ?? '#007bff'),
                'description' => $event->description ?? 'No description provided',
                'status' => $status,
            ];
        });

        return response()->json($formatted);
    }

    // Show the event creation form
    public function create()
    {
        $title = 'Add Event';
        $heading = 'Create New Event';
        $active = 'calendar';

        return view('admin.calendar.create', compact('title', 'heading', 'active'));
    }

    // Store a new event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        $event = Event::create($validated);

        // If the request was made via AJAX, return JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'event' => $event]);
        }

        return redirect()->route('calendar.index')->with('success', 'Event created successfully!');
    }

    // Show event edit form
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $title = 'Edit Event';
        $heading = 'Edit Calendar Event';
        $active = 'calendar';

        return view('admin.calendar.edit', compact('event', 'title', 'heading', 'active'));
    }

    // Update an existing event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        $event->update($validated);

        // AJAX update support
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('calendar.index')->with('success', 'Event updated successfully!');
    }

    // Delete an event
    public function destroy(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        // If called via AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('calendar.index')->with('success', 'Event deleted successfully!');
    }
}
