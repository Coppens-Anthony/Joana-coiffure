import { Calendar as FullCalendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

let calendar = null;

export const Calendar = {
    init() {
        const calendarEl = document.getElementById('calendar');

        if (!calendarEl) return;
        if (calendar) calendar.destroy();

        let selectedDayEl = null;
        const events = JSON.parse(calendarEl.dataset.events);

        calendar = new FullCalendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin, listPlugin],
            initialView: 'dayGridMonth',
            locale: 'fr',
            timeZone: 'Europe/Brussels',
            firstDay: 1,
            events: events,
            dayMaxEvents: true,
            buttonText: { today: "Aujourd'hui" },
            selectable: true,

            datesSet(info) {
                Livewire.dispatch('data-set', {
                    firstDay: info.startStr,
                    lastDay: info.endStr
                });
            },

            select(info) {
                const endDate = new Date(info.end);
                endDate.setDate(endDate.getDate() - 1);
                const endStr = endDate.toISOString().split('T')[0];
                if (info.startStr === endStr) return;
                Livewire.dispatch('unavailabilities-selected', { start: info.startStr, end: endStr });
            },
            navLinks: true,

            navLinkDayClick(date) {
                if (selectedDayEl) selectedDayEl.classList.remove('selected-day');
                Livewire.dispatch('date-selected', {
                    date: date.toISOString().split('T')[0]
                });
            },

            dateClick(info) {
                if (selectedDayEl) selectedDayEl.classList.remove('selected-day');
                info.dayEl.classList.add('selected-day');
                selectedDayEl = info.dayEl;
                Livewire.dispatch('date-selected', { date: info.dateStr });
            }
        });

        calendar.render();

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) calendar.changeView('dayGridMonth');
            else calendar.changeView('listWeek');
        });

        Livewire.on('refresh-calendar', ({ events }) => {
            calendar.removeAllEvents();
            calendar.addEventSource(events);
        });
    },

    getInitialView() {
        return window.innerWidth >= 1024 ? 'dayGridMonth' : 'listWeek';
    }
};


const slots = document.getElementById('slots');
if (slots && window.location.hash === '#slots') {
    const navHeight = document.querySelector('nav')?.offsetHeight;
    const y = slots.getBoundingClientRect().top + window.scrollY - (navHeight + 300);
    window.scrollTo({ top: y, behavior: 'smooth' });
}
