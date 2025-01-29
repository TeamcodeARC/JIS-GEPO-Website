// assets/js/calendar.js

class EventsCalendar {
    constructor() {
        this.currentView = 'month';
        this.currentDate = new Date();
        this.events = [];
        this.initializeCalendar();
        this.bindEventListeners();
        this.fetchEvents();
    }

    initializeCalendar() {
        this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'tailwind',
            events: this.events,
            editable: this.isAdmin(),
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true,
            eventClick: this.handleEventClick.bind(this),
            select: this.handleDateSelect.bind(this),
            eventDrop: this.handleEventDrop.bind(this),
            eventResize: this.handleEventResize.bind(this),
            loading: this.handleLoading.bind(this)
        });

        this.calendar.render();
    }

    async fetchEvents() {
        try {
            const response = await fetch('/api/events/get-events.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    start: this.calendar.view.activeStart,
                    end: this.calendar.view.activeEnd
                })
            });

            if (!response.ok) throw new Error('Failed to fetch events');
            
            const events = await response.json();
            this.events = events.map(event => ({
                id: event.id,
                title: event.title,
                start: event.start_date,
                end: event.end_date,
                color: this.getEventColor(event.type),
                extendedProps: {
                    description: event.description,
                    location: event.location,
                    virtualLink: event.virtual_link,
                    maxParticipants: event.max_participants,
                    currentParticipants: event.registrations_count,
                    type: event.type
                }
            }));

            this.calendar.removeAllEvents();
            this.calendar.addEventSource(this.events);
        } catch (error) {
            console.error('Error fetching events:', error);
            this.showNotification('Error loading events', 'error');
        }
    }

    handleEventClick(info) {
        const event = info.event;
        const modal = new EventModal({
            title: event.title,
            description: event.extendedProps.description,
            start: event.start,
            end: event.end,
            location: event.extendedProps.location,
            virtualLink: event.extendedProps.virtualLink,
            maxParticipants: event.extendedProps.maxParticipants,
            currentParticipants: event.extendedProps.currentParticipants,
            type: event.extendedProps.type,
            isAdmin: this.isAdmin(),
            onRegister: () => this.registerForEvent(event.id),
            onEdit: () => this.editEvent(event),
            onDelete: () => this.deleteEvent(event.id)
        });
        modal.show();
    }

    async registerForEvent(eventId) {
        try {
            const response = await fetch('/api/events/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ eventId })
            });

            if (!response.ok) throw new Error('Registration failed');

            const result = await response.json();
            this.showNotification(result.message, 'success');
            this.fetchEvents(); // Refresh events to update counts
        } catch (error) {
            console.error('Registration error:', error);
            this.showNotification('Failed to register for event', 'error');
        }
    }

    handleDateSelect(selectInfo) {
        if (!this.isAdmin()) return;

        const modal = new EventModal({
            mode: 'create',
            start: selectInfo.start,
            end: selectInfo.end,
            onSave: async (eventData) => {
                try {
                    const response = await fetch('/api/events/create.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(eventData)
                    });

                    if (!response.ok) throw new Error('Failed to create event');

                    this.showNotification('Event created successfully', 'success');
                    this.fetchEvents();
                } catch (error) {
                    console.error('Error creating event:', error);
                    this.showNotification('Failed to create event', 'error');
                }
            }
        });
        modal.show();
    }

    handleEventDrop(info) {
        this.updateEventDates(info.event);
    }

    handleEventResize(info) {
        this.updateEventDates(info.event);
    }

    async updateEventDates(event) {
        try {
            const response = await fetch('/api/events/update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: event.id,
                    start: event.start,
                    end: event.end
                })
            });

            if (!response.ok) throw new Error('Failed to update event');

            this.showNotification('Event updated successfully', 'success');
        } catch (error) {
            console.error('Error updating event:', error);
            this.showNotification('Failed to update event', 'error');
            this.fetchEvents(); // Revert changes
        }
    }

    getEventColor(type) {
        const colors = {
            workshop: '#3B82F6', // blue
            seminar: '#10B981', // green
            conference: '#F59E0B', // yellow
            cultural: '#EC4899', // pink
            default: '#6B7280' // gray
        };
        return colors[type] || colors.default;
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            'bg-blue-500'
        } text-white`;
        notification.textContent = message;

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    isAdmin() {
        return document.body.dataset.userRole === 'admin';
    }
}

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new EventsCalendar();
});