import('./appointment_services');
import('./gallery');
import('./password');
import('./calendar');
import('./chart');

document.body.classList.add('js');

import { initRevenueChart } from './chart';

document.addEventListener('DOMContentLoaded', () => {
    initRevenueChart();
});

function initCalendarIfNeeded() {
    if (document.getElementById('calendar') || document.getElementById('user_calendar')) {
        import('./calendar.js').then(({ Calendar, MemberCalendar }) => {
            if (document.getElementById('calendar')) {
                Calendar.init();
            }
            if (document.getElementById('user_calendar')) {
                MemberCalendar.init();
            }
        });
    }
}

document.addEventListener('livewire:navigated', initCalendarIfNeeded);

