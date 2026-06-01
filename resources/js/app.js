import('./appointment_services');
import('./gallery');
import('./password');
import('./calendar');

document.body.classList.add('js');

function initCalendarIfNeeded() {
    if (! document.getElementById('calendar')) {
        return;
    }
    import('./calendar.js').then(({ Calendar }) => Calendar.init());
}

document.addEventListener('livewire:navigated', initCalendarIfNeeded);
