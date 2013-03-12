
//var googleDecodedPath = [];
//var googleEncodedPath;
//var googleElevationForLocations; //asynchronous
var pathInGeoJSON = {
	"type": "LineString",
	"coordinates": []
};
var pathForMySQL; 			//example: LineString(0 0,0 3,3 3,3 0,0 0)
var placePointAForMySQL; 	//example: POINT(31.5 42.2)
var placePointBForMySQL;

// Vars with facts about the hike
var pathLength = 0;
var pathMaxElevation = 0;
var pathMinElevation = 0;
var pathElevationDifference = 0;
var pathElevationIncrease = 0;
var pathElevationDecrease = 0;
var estimatedDurationAtoB = 0;
var estimatedDurationBtoA = 0;
var hikeDifficultyFromAToB = "N/A";
var hikeDifficultyFromAToBToA = "N/A";
var hikeDifficultyFromBToA = "N/A";


function initialize_vars(path){

	setPlacePointForMySQL(path);

	pathForMySQL = "LineString(";
	
	//SET var
	for (var i = 0; i < path.length; i++) {
		leafletPath.push(new L.LatLng(path[i][0], path[i][1]));
		pathInGeoJSON.coordinates.push([Number(path[i][1]), Number(path[i][0])]);
		pathForMySQL += Number(path[i][0]) +" "+ Number(path[i][1]) +",";
	} //END for

	pathForMySQL = pathForMySQL.slice(0, -1); //remove last char, which is a ","
	pathForMySQL += ")";



} //END initialize_vars()



function updateHikeName(element){

	if(element != undefined){
		// get input from form
		hikeName = document.getElementById(element.id).value;
	}

	// remove current label from map
	polyline.unbindLabel();

	// update on map
	if(hikeName.length == 0){
		polyline.bindLabel(defaultHikeName, { noHide: true });
	}
	else {
		polyline.bindLabel(hikeName, { noHide: true });
	}

	updateHikeFactBox();

} //END updateHikeName()


function updateHikeDescription(element){

	if(element != undefined){
		// get input from form
		hikeDescription = document.getElementById(element.id).value;
	}

	updateHikeFactBox();

} //END updateHikeDescription()



function updatePlaceNameA(element){

	if(element != undefined){
		// get input from form
		placeNameA = document.getElementById(element.id).value;
	}

	// remove current label from map
	leaflet_marker_place_a.unbindLabel();
	
	// update on map
	if(placeNameA.length == 0){
		leaflet_marker_place_a.bindLabel(defaultPlaceNameA, { noHide: true }).showLabel();
	}
	else {
		leaflet_marker_place_a.bindLabel(placeNameA, { noHide: true }).showLabel();
	}

	updateHikePathFactBox();

} //END updatePathNameA()

function updatePlaceNameB(element){

	if(element != undefined){
		// get input from form
		placeNameB = document.getElementById(element.id).value;
	}

	// remove current label from map
	leaflet_marker_place_b.unbindLabel();
	
	// update on map
	if(placeNameB.length == 0){
		leaflet_marker_place_b.bindLabel(defaultPlaceNameB, { noHide: true }).showLabel();
	}
	else {
		leaflet_marker_place_b.bindLabel(placeNameB, { noHide: true }).showLabel();
	}

	updateHikePathFactBox();

} //END updatePathNameB()


function setPlacePointForMySQL(path){

	placePointAForMySQL = "POINT("+ path[0][0] +", "+ path[0][1] +")";
	placePointBForMySQL = "POINT("+ path[path.length-1][0] +", "+ path[path.length-1][1] +")";

} //END setPlacePointForMySQL()


function setVarLeafletPath(jsonEncodedPath){

	for (var i = 0; i < jsonEncodedPath.length; i++) {
		leafletPath.push(new L.LatLng(jsonEncodedPath[i]['lat'], jsonEncodedPath[i]['lng']));
	}

} //END setVarLeafletPath(jsonEncodedPath)


function convertTime(dec){
	var seconds = dec * 3600;
	var hours = Math.floor(dec);
		seconds -= hours * 3600;
	var minutes = Math.floor(seconds / 60);
		seconds -= minutes * 60;
	return hours +"h "+ minutes +"m";
} //END convertTime()