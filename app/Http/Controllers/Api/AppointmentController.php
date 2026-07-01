<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CalenderResource;
use App\Models\CalendarEvent;
use Auth;
use Illuminate\Http\Request;

class AppointmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $appointments = CalendarEvent::where('user_id', Auth::id())->get();

            return $this->sendResponse(CalenderResource::collection($appointments), 'Appointments retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Appointments could not be retrieved.', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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

            return $this->sendResponse(new CalenderResource($event), 'Appointment created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Appointment could not be created.', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $event = CalendarEvent::findOrFail($id);

            return $this->sendResponse(new CalenderResource($event), 'Appointment retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Appointment could not be retrieved.', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
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
            $event = CalendarEvent::findOrFail($id);
            $event->update($validated);

            return $this->sendResponse(new CalenderResource($event), 'Appointment updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Appointment could not be updated.', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $event = CalendarEvent::findOrFail($id);
            $event->delete();

            return $this->sendResponse([], 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Appointment could not be deleted.', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
