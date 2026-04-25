const teg = [41.3059, 19.8216];

const map = L.map('map', {
  scrollWheelZoom: false
}).setView(teg, 16);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

L.marker(teg)
  .addTo(map)
  .bindPopup('<b>Maison de Parfum</b><br>TEG – Tirana East Gate');
