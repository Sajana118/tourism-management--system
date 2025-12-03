// Function to initialize charts with data from HTML data attributes
document.addEventListener('DOMContentLoaded', function() {
    // Bookings Chart
    const bookingsDataElement = document.getElementById('bookings-chart-data');
    if (bookingsDataElement) {
        const bookingsData = JSON.parse(bookingsDataElement.dataset.bookings);
        
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        new Chart(bookingsCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Confirmed', 'Cancelled'],
                datasets: [{
                    label: 'Bookings',
                    data: bookingsData,
                    backgroundColor: [
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        '#8b5cf6',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Packages Chart
    const packagesDataElement = document.getElementById('packages-chart-data');
    if (packagesDataElement) {
        const packagesData = JSON.parse(packagesDataElement.dataset.packages);
        
        const packagesCtx = document.getElementById('packagesChart').getContext('2d');
        new Chart(packagesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active Packages', 'Total Bookings'],
                datasets: [{
                    data: packagesData,
                    backgroundColor: [
                        'rgba(220, 20, 60, 0.7)',
                        'rgba(0, 56, 147, 0.7)'
                    ],
                    borderColor: [
                        '#DC143C',
                        '#003893'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});