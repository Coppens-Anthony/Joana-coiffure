import { Chart } from 'chart.js/auto';

export function initRevenueChart() {
    const canvas = document.getElementById('chart');
    if (!canvas) return;

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: JSON.parse(canvas.dataset.labels),
            datasets: [
                {
                    label: canvas.dataset.year1Label,
                    data: JSON.parse(canvas.dataset.year1),
                    borderColor: 'rgb(255 0 0)',
                    tension: 0.3,
                    fill: false,
                },
                {
                    label: canvas.dataset.year2Label,
                    data: JSON.parse(canvas.dataset.year2),
                    borderColor: 'rgb(255 140 0)',
                    tension: 0.3,
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => value.toLocaleString('fr-BE') + ' €',
                    },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString('fr-BE')} €`,
                    },
                },
            },
        },
    });
}
