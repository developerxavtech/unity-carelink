<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CalendarEventController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource (API endpoint for FullCalendar).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $query = CalendarEvent::forUser(Auth::id());

        // Filter by date range if provided
        if ($request->has('start') && $request->has('end')) {
            $query->dateRange($request->start, $request->end);
        }

        $events = $query->get();

        // Return in FullCalendar format
        return response()->json(
            $events->map(fn($event) => $event->toFullCalendarFormat())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', CalendarEvent::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:meeting,appointment,reminder,other',
            'color' => 'nullable|string|max:7',
            'all_day' => 'boolean',
            'reminder_minutes' => 'nullable|integer|min:0',
        ]);

        $event = CalendarEvent::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event' => $event->toFullCalendarFormat(),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CalendarEvent $calendarEvent)
    {
        $this->authorize('view', $calendarEvent);

        return response()->json([
            'success' => true,
            'event' => $calendarEvent->load('user'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $this->authorize('update', $calendarEvent);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'sometimes|required|date',
            'end_datetime' => 'sometimes|required|date|after:start_datetime',
            'location' => 'nullable|string|max:255',
            'event_type' => 'sometimes|required|in:meeting,appointment,reminder,other',
            'color' => 'nullable|string|max:7',
            'all_day' => 'boolean',
            'reminder_minutes' => 'nullable|integer|min:0',
        ]);

        $calendarEvent->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'event' => $calendarEvent->fresh()->toFullCalendarFormat(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CalendarEvent $calendarEvent)
    {
        $this->authorize('delete', $calendarEvent);

        $calendarEvent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully',
        ]);
    }
}
