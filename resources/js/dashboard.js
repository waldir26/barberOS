import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const chartCanvas = document.getElementById('ingresosChart');

    if (chartCanvas) {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';

        const fechas = JSON.parse(chartCanvas.dataset.fechas);
        const ingresos = JSON.parse(chartCanvas.dataset.ingresos);

        new Chart(chartCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Ingresos ($)',
                    data: ingresos,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: isDark ? '#1e293b' : '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor },
                        grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            }
        });
    }
});