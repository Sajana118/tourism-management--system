// Initialize Nepal Map
const map = L.map('nepalMap').setView([28.3949, 84.1240], 7);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 18
}).addTo(map);

const packageLocations = [
    { name: 'Kathmandu', coords: [27.7172, 85.3240], packages: 'Heritage Tours' },
    { name: 'Pokhara', coords: [28.2096, 83.9856], packages: 'Adventure & Lake' },
    { name: 'Everest', coords: [27.9881, 86.9250], packages: 'Trekking' },
    { name: 'Chitwan', coords: [27.5291, 84.3542], packages: 'Wildlife Safari' },
    { name: 'Lumbini', coords: [27.4833, 83.2767], packages: 'Spiritual Tours' },
    { name: 'Annapurna', coords: [28.5967, 83.8206], packages: 'Base Camp Trek' }
];

packageLocations.forEach(location => {
    const marker = L.marker(location.coords).addTo(map);
    marker.bindPopup(`
        <div class="map-popup">
            <h6><i class="fas fa-map-marker-alt"></i> ${location.name}</h6>
            <p><i class="fas fa-tags"></i> ${location.packages}</p>
        </div>
    `);
});