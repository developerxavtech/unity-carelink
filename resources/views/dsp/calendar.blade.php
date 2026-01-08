<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>My Calendar</span>
            <button class="btn btn-primary" id="addEventBtn">
                <i class="bi bi-plus-circle"></i> Add Event
            </button>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-body">
                    <!-- FullCalendar Container -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <input type="hidden" id="eventId" name="event_id">

                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eventTitle" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="eventStartDate" class="form-label">Start Date <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="eventStartDate"
                                    name="start_datetime" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="eventEndDate" class="form-label">End Date <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="eventEndDate" name="end_datetime"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="eventLocation" name="location">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="eventType" class="form-label">Event Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="eventType" name="event_type" required>
                                    <option value="meeting">Meeting</option>
                                    <option value="appointment">Appointment</option>
                                    <option value="reminder">Reminder</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="eventColor" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="eventColor"
                                    name="color" value="#3788d8">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="eventAllDay" name="all_day">
                                    <label class="form-check-label" for="eventAllDay">
                                        All Day Event
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="eventReminder" class="form-label">Reminder (minutes before)</label>
                                <select class="form-select" id="eventReminder" name="reminder_minutes">
                                    <option value="">No reminder</option>
                                    <option value="15">15 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="60">1 hour</option>
                                    <option value="120">2 hours</option>
                                    <option value="1440">1 day</option>
                                    <option value="2880">2 days</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteEventBtn" style="display: none;">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEventBtn">Save Event</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.10/index.global.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                const eventForm = document.getElementById('eventForm');
                let currentEvent = null;

                // Initialize FullCalendar
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    themeSystem: 'bootstrap5',
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    weekends: true,
                    events: function(info, successCallback, failureCallback) {
                        // Fetch events from API
                        fetch(`/calendar-events?start=${info.startStr}&end=${info.endStr}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => successCallback(data))
                            .catch(error => {
                                console.error('Error fetching events:', error);
                                failureCallback(error);
                            });
                    },
                    select: function(info) {
                        // Open modal for new event
                        openEventModal(null, info.startStr, info.endStr);
                        calendar.unselect();
                    },
                    eventClick: function(info) {
                        // Open modal for editing event
                        openEventModal(info.event);
                    },
                    eventDrop: function(info) {
                        // Update event when dragged
                        updateEvent(info.event);
                    },
                    eventResize: function(info) {
                        // Update event when resized
                        updateEvent(info.event);
                    }
                });

                calendar.render();

                // Add Event Button
                document.getElementById('addEventBtn').addEventListener('click', function() {
                    openEventModal();
                });

                // Save Event Button
                document.getElementById('saveEventBtn').addEventListener('click', function() {
                    saveEvent();
                });

                // Delete Event Button
                document.getElementById('deleteEventBtn').addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this event?')) {
                        deleteEvent();
                    }
                });

                function openEventModal(event = null, startStr = null, endStr = null) {
                    currentEvent = event;
                    eventForm.reset();

                    if (event) {
                        // Edit mode
                        document.getElementById('eventModalLabel').textContent = 'Edit Event';
                        document.getElementById('eventId').value = event.id;
                        document.getElementById('eventTitle').value = event.title;
                        document.getElementById('eventDescription').value = event.extendedProps.description || '';
                        document.getElementById('eventStartDate').value = formatDateTimeLocal(event.start);
                        document.getElementById('eventEndDate').value = formatDateTimeLocal(event.end || event.start);
                        document.getElementById('eventLocation').value = event.extendedProps.location || '';
                        document.getElementById('eventType').value = event.extendedProps.event_type || 'other';
                        document.getElementById('eventColor').value = event.backgroundColor || '#3788d8';
                        document.getElementById('eventAllDay').checked = event.allDay || false;
                        document.getElementById('eventReminder').value = event.extendedProps.reminder_minutes || '';
                        document.getElementById('deleteEventBtn').style.display = 'block';
                    } else {
                        // Create mode
                        document.getElementById('eventModalLabel').textContent = 'Add Event';
                        document.getElementById('deleteEventBtn').style.display = 'none';

                        if (startStr) {
                            document.getElementById('eventStartDate').value = formatDateTimeLocal(new Date(startStr));
                            document.getElementById('eventEndDate').value = formatDateTimeLocal(new Date(endStr ||
                                startStr));
                        } else {
                            const now = new Date();
                            const oneHourLater = new Date(now.getTime() + 60 * 60 * 1000);
                            document.getElementById('eventStartDate').value = formatDateTimeLocal(now);
                            document.getElementById('eventEndDate').value = formatDateTimeLocal(oneHourLater);
                        }
                    }

                    eventModal.show();
                }

                function saveEvent() {
                    const formData = new FormData(eventForm);
                    const eventId = document.getElementById('eventId').value;
                    const url = eventId ? `/calendar-events/${eventId}` : '/calendar-events';
                    const method = eventId ? 'PUT' : 'POST';

                    const data = {};
                    formData.forEach((value, key) => {
                        if (key === 'all_day') {
                            data[key] = document.getElementById('eventAllDay').checked;
                        } else if (value !== '') {
                            data[key] = value;
                        }
                    });

                    fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                calendar.refetchEvents();
                                eventModal.hide();
                                showAlert('success', data.message);
                            } else {
                                showAlert('danger', 'Error saving event');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'Error saving event');
                        });
                }

                function updateEvent(event) {
                    const data = {
                        start_datetime: event.start.toISOString(),
                        end_datetime: (event.end || event.start).toISOString()
                    };

                    fetch(`/calendar-events/${event.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('success', 'Event updated successfully');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            event.revert();
                            showAlert('danger', 'Error updating event');
                        });
                }

                function deleteEvent() {
                    const eventId = document.getElementById('eventId').value;

                    fetch(`/calendar-events/${eventId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                calendar.refetchEvents();
                                eventModal.hide();
                                showAlert('success', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'Error deleting event');
                        });
                }

                function formatDateTimeLocal(date) {
                    const d = new Date(date);
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    return `${year}-${month}-${day}T${hours}:${minutes}`;
                }

                function showAlert(type, message) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className =
                        `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
                    alertDiv.style.zIndex = '9999';
                    alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => alertDiv.remove(), 3000);
                }
            });
        </script>
    @endpush
</x-app-layout>
