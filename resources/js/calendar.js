import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('livewire:navigated', () => {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) return;
    let selectedDayEl = null;

    const events = JSON.parse(calendarEl.dataset.events);

    const calendar = new Calendar(calendarEl, {
        plugins: [
            dayGridPlugin,
            interactionPlugin
        ],

        initialView: 'dayGridMonth',

        locale: 'fr',
        timeZone: 'Europe/Brussels',
        firstDay: 1,

        events: events,
        dayMaxEvents: true,


        dateClick(info) {
            if (selectedDayEl) {
                selectedDayEl.classList.remove('selected-day');
            }

            info.dayEl.classList.add('selected-day');
            selectedDayEl = info.dayEl;

            Livewire.dispatch('date-selected', {
                date: info.dateStr
            });
        }
    });

    calendar.render();
});
