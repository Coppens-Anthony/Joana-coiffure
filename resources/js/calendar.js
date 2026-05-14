import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

document.addEventListener('livewire:navigated', () => {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) return;

    const events = JSON.parse(calendarEl.dataset.events);

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        locale: 'fr',
        timeZone: 'Europe/Brussels',
        firstDay: 1,
        events: events,
        dayMaxEvents: true,
    });

    calendar.render();
});
