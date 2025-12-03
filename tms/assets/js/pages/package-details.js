// Initialize Leaflet Map for this package
document.addEventListener('DOMContentLoaded', function() {
    const locationCoords = {
        'Kathmandu': [27.7172, 85.3240],
        'Pokhara': [28.2096, 83.9856],
        'Everest': [27.9881, 86.9250],
        'Chitwan': [27.5291, 84.3542],
        'Lumbini': [27.4833, 83.2767],
        'Nagarkot': [27.7172, 85.5206],
        'Mustang': [29.1840, 83.9856],
        'Langtang': [28.2167, 85.5500],
        'Rara': [29.5267, 82.0803],
        'Annapurna': [28.5967, 83.8206]
    };
    
    // Get PHP variables safely
    const packageLocation = document.getElementById('package-location-data').dataset.location;
    const packageName = document.getElementById('package-name-data').dataset.name;
    
    // Find coordinates based on location
    let coords = [27.7172, 85.3240]; // Default to Kathmandu
    for (let loc in locationCoords) {
        if (packageLocation.includes(loc)) {
            coords = locationCoords[loc];
            break;
        }
    }
    
    // Initialize map
    const map = L.map('locationMap').setView(coords, 10);
    
    // Add OpenStreetMap tiles (free, no API key needed)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);
    
    // Custom marker icon (Nepal flag color)
    const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    
    // Add marker
    const marker = L.marker(coords, {icon: redIcon}).addTo(map);
    marker.bindPopup(`
        <div class="map-marker-popup">
            <h6>${packageName}</h6>
            <p><i class="fas fa-map-marker-alt"></i> ${packageLocation}</p>
        </div>
    `).openPopup();
    
    // Add Nepal boundary highlight
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
});

// Initialize datepickers
$(function() {
    $("#datepicker, #datepicker1").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0,
        onSelect: function(selectedDate) {
            if(this.id === 'datepicker') {
                $('#datepicker1').datepicker('option', 'minDate', selectedDate);
            }
        }
    });
});