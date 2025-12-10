import './bootstrap';

// Import Bootstrap
import * as bootstrap from 'bootstrap';

// Import Chart.js
import Chart from 'chart.js/auto';

// Import Alpine.js (from Breeze)
import Alpine from 'alpinejs';

// Make available globally
window.bootstrap = bootstrap;
window.Chart = Chart;
window.Alpine = Alpine;

Alpine.start();
