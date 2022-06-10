mapboxgl.accessToken = 'pk.eyJ1IjoiZXZhbWVyY2llcjQiLCJhIjoiY2wydXNobTNsMDUyODNjamprdXJzdHF1ciJ9.nxPyMpEFfWXFhOyW0p6vzg';
var mymap = new mapboxgl.Map({
    container: 'my_osm_widget_map',
    style: 'mapbox://styles/mapbox/streets-v10', 
    center: [-1.322041620440115, 48.400684036814155], 
    zoom: 10,
    zoomControl: true,
    minZoom: 7,
});

new mapboxgl.Marker()
    .setLngLat([-1.322041620440115, 48.400684036814155])
    .addTo(mymap);