import {Calendar as FullCalendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

const commonConfig = {
    plugins: [dayGridPlugin, interactionPlugin, listPlugin, timeGridPlugin],
    initialView: 'dayGridMonth',
    locale: 'fr',
    timeZone: 'Europe/Brussels',
    firstDay: 1,
    headerToolbar: {
        left: 'dayGridMonth,timeGridWeek,timeGridDay',
        right: 'today,prev,next',
        center: 'title',
    },
    dayMaxEvents: true,
    buttonText: {
        today: "Aujourd'hui",
        month: 'Mois',
        week: 'Semaine',
        day: 'Jour',
    },
    navLinks: true,
};

function getInitialView() {
    return window.innerWidth >= 1024 ? 'dayGridMonth' : 'listWeek';
}


let calendar = null;

export const Calendar = {
    init() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        if (calendar) calendar.destroy();

        let selectedDayEl = null;
        const events = JSON.parse(calendarEl.dataset.events);

        calendar = new FullCalendar(calendarEl, {
            ...commonConfig,
            initialView: getInitialView(),
            events: events,
            selectable: true,

            eventClick(info) {
                const id = info.event.id;
                const type = info.event.extendedProps.type;
                if (type === 'appointment') {
                    Livewire.dispatch('show-appointment', {id});
                }
            },

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
                Livewire.dispatch('unavailabilities-selected', {start: info.startStr, end: endStr});
            },

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
                Livewire.dispatch('date-selected', {date: info.dateStr});
            }
        });

        calendar.render();

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) calendar.changeView('dayGridMonth');
            else calendar.changeView('listWeek');
        });

        Livewire.on('refresh-calendar', ({events}) => {
            calendar.removeAllEvents();
            calendar.addEventSource(events);
        });
    },
};


let memberCalendar = null;

export const MemberCalendar = {
    init() {
        const calendarEl = document.getElementById('user_calendar');
        if (!calendarEl) return;
        if (memberCalendar) memberCalendar.destroy();

        let selectedDayEl = null;
        const events = JSON.parse(calendarEl.dataset.events);

        memberCalendar = new FullCalendar(calendarEl, {
            ...commonConfig,
            initialView: getInitialView(),
            events: events,
            selectable: false,
            editable: false,
            eventStartEditable: false,
            eventDurationEditable: false,


            datesSet(info) {
                Livewire.dispatch('data-set', {
                    firstDay: info.startStr,
                    lastDay: info.endStr
                });
            },

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
                Livewire.dispatch('date-selected', {date: info.dateStr});
            }
        });

        memberCalendar.render();

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) memberCalendar.changeView('dayGridMonth');
            else memberCalendar.changeView('listWeek');
        });

        Livewire.on('refresh-calendar', ({events}) => {
            memberCalendar.removeAllEvents();
            memberCalendar.addEventSource(events);
        });
    }
};


const slots = document.getElementById('slots');
if (slots && window.location.hash === '#slots') {
    const navHeight = document.querySelector('nav')?.offsetHeight;
    const y = slots.getBoundingClientRect().top + window.scrollY - (navHeight + 360);
    window.scrollTo({top: y, behavior: 'smooth'});
}
