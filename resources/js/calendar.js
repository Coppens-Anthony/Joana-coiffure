import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

let calendar = null;

document.addEventListener('livewire:navigated', () => {
    const calendarEl = document.getElementById('calendar');

    if (!calendarEl || calendar) return;

    let selectedDayEl = null;

    const events = JSON.parse(calendarEl.dataset.events);

    calendar = new Calendar(calendarEl, {
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

        buttonText: {
            today: 'Aujourd\'hui'
        },
        selectable: true,



        select(info) {
            const endDate = new Date(info.end);
            endDate.setDate(endDate.getDate() - 1);
            const endStr = endDate.toISOString().split('T')[0];

            if (info.startStr === endStr) return;

            Livewire.dispatch('unavailabilities-selected', {
                start: info.startStr,
                end: endStr
            });
        },

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

    Livewire.on('refresh-calendar', ({ events }) => {
        calendar.removeAllEvents();
        calendar.addEventSource(events);
    });
});
