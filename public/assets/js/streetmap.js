let map = L.map('map').setView([48.400523, -1.322081], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    minZoom: 9,
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker = L.marker([48.400523, -1.322081]).addTo(map);