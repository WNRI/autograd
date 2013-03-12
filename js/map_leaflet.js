var map;
var polyline;
var leaflet_marker_place_a;
var leaflet_marker_place_b;

var markerGroup = new L.LayerGroup();
var marker;

function initialize_map() {

	// create a map in the "map" div, set the view to a given place and zoom
	map = L.map('map-div', {
		center: [61.2706935, 7.160778700000037],
		zoom: 13,
		scrollWheelZoom: false
	});

	map.addLayer(markerGroup);

	// map from turkompisen.no
	L.tileLayer('http://kart{s}.turkompisen.no/cgi-bin/tilecache.cgi/1.0.0/turkart/{z}/{x}/{y}.png', {
	    attribution: '&copy; <a href="http://osm.org/copyright" target="_new">OpenStreetMap</a>, <a href="http://www.skogoglandskap.no" target="_new">Skogoglandskap</a>, <a href="http://www.turkompisen.no" target="_new">Turkompisen</a>',
	    subdomains: ["1", "2", "3", "4"],
		maxZoom: 18
	}).addTo(map);

	// create polyline and add to map
	polyline = L.polyline(leafletPath).addTo(map);
	updateHikeName();

	// zoom the map to the polyline
	map.fitBounds(polyline.getBounds());

	// SET markers on map
	leaflet_marker_place_a = new L.marker(leafletPath[0]).addTo(map);
	updatePlaceNameA();

	leaflet_marker_place_b = new L.marker(leafletPath[leafletPath.length-1]).addTo(map);
	updatePlaceNameB();

} //END initialize_map()