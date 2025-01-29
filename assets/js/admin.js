// assets/js/admin.js

import { Chart } from 'recharts';

class AdminDashboard {
    constructor() {
        this.initializeCharts();
        this.initializeEventListeners();
        this.initializeDataTables();
    }

    initializeCharts() {
        // Partner Distribution Chart
        const partnerChart = new Chart(
            document.getElementById('partnerDistribution'),
            {
                type: 'doughnut',
                data: {
                    labels: ['Universities', 'Research Institutes', 'Corporate Partners'],
                    datasets: [{
                        data: [65, 20, 15],
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
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
            }
        );

        // Monthly Activity Chart
        fetch('/api/dashboard/activity-stats.php')
            .then(response => response.json())
            .then(data => {
                const activityChart = new Chart(
                    document.getElementById('monthlyActivity'),
                    {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'User Activity',
                                data: data.values,
                                borderColor: '#3B82F6',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    }
                );
            });
    }

    initializeEventListeners() {
        // Quick Actions
        document.querySelectorAll('.quick-action').forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.currentTarget.dataset.action;
                this.handleQuickAction(action);
            });
        });

        // Search Functionality
        const searchInput = document.querySelector('#dashboardSearch');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce((e) => {
                this.handleSearch(e.target.value);
            }, 300));
        }

        // Notification System
        this.initializeNotifications();
    }

    initializeDataTables() {
        // Initialize all data tables with search and sort functionality
        document.querySelectorAll('.data-table').forEach(table => {
            new DataTable(table, {
                pageLength: 10,
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf']
            });
        });
    }

    handleQuickAction(action) {
        const actions = {
            'new-partner': () => this.openModal('partnerForm'),
            'new-program': () => this.openModal('programForm'),
            'new-event': () => this.openModal('eventForm')
        };

        if (actions[action]) {
            actions[action]();
        }
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
    }

    handleSearch(query) {
        if (query.length < 2) return;

        fetch(`/api/dashboard/search.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(results => {
                this.updateSearchResults(results);
            });
    }

    updateSearchResults(results) {
        const container = document.querySelector('#searchResults');
        container.innerHTML = '';

        results.forEach(result => {
            const element = document.createElement('div');
            element.classList.add('search-result');
            element.innerHTML = `
                <div class="p-4 hover:bg-gray-50 cursor-pointer">
                    <h4 class="text-lg font-medium">${result.title}</h4>
                    <p class="text-gray-600">${result.description}</p>
                </div>
            `;
            container.appendChild(element);
        });
    }

    initializeNotifications() {
        // WebSocket connection for real-time notifications
        const ws = new WebSocket('wss://your-domain.com/ws');

        ws.onmessage = (event) => {
            const notification = JSON.parse(event.data);
            this.showNotification(notification);
        };
    }

    showNotification(notification) {
        const container = document.querySelector('#notifications');
        const element = document.createElement('div');
        element.classList.add('notification', 'animate-slide-in');
        element.innerHTML = `
            <div class="bg-white p-4 rounded-lg shadow-lg mb-4">
                <h5 class="font-medium">${notification.title}</h5>
                <p class="text-gray-600">${notification.message}</p>
            </div>
        `;
        container.appendChild(element);

        setTimeout(() => {
            element.classList.add('animate-slide-out');
            setTimeout(() => element.remove(), 300);
        }, 5000);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});