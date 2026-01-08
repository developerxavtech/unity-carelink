<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'event_type',
        'color',
        'all_day',
        'recurrence_rule',
        'reminder_minutes',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'all_day' => 'boolean',
            'reminder_minutes' => 'integer',
        ];
    }

    /**
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter events by date range.
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_datetime', [$start, $end])
                ->orWhereBetween('end_datetime', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start_datetime', '<=', $start)
                        ->where('end_datetime', '>=', $end);
                });
        });
    }

    /**
     * Scope to filter events for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc');
    }

    /**
     * Get formatted start date for display.
     */
    public function getFormattedStartAttribute(): string
    {
        return $this->start_datetime->format('M d, Y g:i A');
    }

    /**
     * Get formatted end date for display.
     */
    public function getFormattedEndAttribute(): string
    {
        return $this->end_datetime->format('M d, Y g:i A');
    }

    /**
     * Get event duration in minutes.
     */
    public function getDurationInMinutesAttribute(): int
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime);
    }

    /**
     * Convert event to FullCalendar format.
     */
    public function toFullCalendarFormat(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start_datetime->toIso8601String(),
            'end' => $this->end_datetime->toIso8601String(),
            'allDay' => $this->all_day,
            'backgroundColor' => $this->color ?? $this->getDefaultColor(),
            'borderColor' => $this->color ?? $this->getDefaultColor(),
            'extendedProps' => [
                'description' => $this->description,
                'location' => $this->location,
                'event_type' => $this->event_type,
                'reminder_minutes' => $this->reminder_minutes,
            ],
        ];
    }

    /**
     * Get default color based on event type.
     */
    protected function getDefaultColor(): string
    {
        return match ($this->event_type) {
            'meeting' => '#3788d8',
            'appointment' => '#28a745',
            'reminder' => '#ffc107',
            'other' => '#6c757d',
            default => '#6c757d',
        };
    }
}

