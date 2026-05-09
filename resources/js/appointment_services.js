const checkboxes = document.querySelectorAll('.service-checkbox');

const servicesContainer = document.getElementById('selected-services');

const emptyMessage = document.getElementById('empty-message');

const appointmentContent = document.getElementById('appointment-content');

const summary = document.getElementById('appointment-summary');

const nextStep = document.querySelector('.next-step');

function formatDuration(totalMinutes) {

    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    if (hours && minutes) {
        return `${hours}h${minutes}`;
    }

    if (hours) {
        return `${hours}h`;
    }

    return `${minutes}min`;
}

document.querySelectorAll('.service-label').forEach(label => {
    label.addEventListener('keydown', (e) => {
        if (e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
            label.click();
        }
    });
});

function updateBottomBar() {

    servicesContainer.innerHTML = '';

    const checked = [...checkboxes].filter(cb => cb.checked);

    if (checked.length === 0) {
        emptyMessage.classList.remove('hidden');
        appointmentContent.classList.add('hidden');
        nextStep.classList.add('hidden');
        return;
    }

    emptyMessage.classList.add('hidden');
    appointmentContent.classList.remove('hidden');
    nextStep.classList.remove('hidden');

    let totalPrice = 0;
    let totalDuration = 0;

    checked.forEach(cb => {

        totalPrice += Number(cb.dataset.price);
        totalDuration += Number(cb.dataset.duration);

        const item = document.createElement('li');
        item.className = 'selected-item flex items-center gap-2 mt-2';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'service-remove-btn';
        btn.setAttribute('aria-label', `Retirer ${cb.dataset.name}`);
        btn.textContent = cb.dataset.name;

        btn.addEventListener('click', () => {
            cb.checked = false;
            updateBottomBar();
        });

        item.appendChild(btn);
        servicesContainer.appendChild(item);
    });

    const count = checked.length;
    summary.textContent =
        `${count} ${count === 1 ? 'prestation sélectionnée' : 'prestations sélectionnées'} `
        + `(${formatDuration(totalDuration)} / ${totalPrice}€)`;
}

checkboxes.forEach(cb => {
    cb.addEventListener('change', updateBottomBar);
});

if (servicesContainer) {
    updateBottomBar();
}
