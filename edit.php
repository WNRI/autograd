<?php

include_once "functions.php";
include_once "connect-db.php";

if (isset($_POST['gpx_file_input'])) {

	// a path has been submitted. more input is needed from the user before adding the hike to the database.
	$jsArray = createJavaScriptPolylineFromGpxFile($_POST['gpx_file_input']);
	renderEditPage($jsArray);

}
else if(
	isset($_POST['hike_name']) AND 
	isset($_POST['place_name_a']) AND 
	isset($_POST['place_name_b']) AND 
	isset($_POST['hike_description']) AND 
	isset($_POST['pathLength']) AND 
	isset($_POST['pathMaxElevation']) AND 
	isset($_POST['pathMinElevation']) AND 
	isset($_POST['pathElevationDifference']) AND 
	isset($_POST['pathElevationIncrease']) AND 
	isset($_POST['pathElevationDecrease']) AND 
	isset($_POST['estimatedDurationAtoB']) AND 
	isset($_POST['estimatedDurationBtoA']) AND 
	isset($_POST['pathForMySQL']) AND 
	isset($_POST['placePointAForMySQL']) AND 
	isset($_POST['placePointBForMySQL']) AND 
	isset($_POST['elevationProfileDataForMySQL']) AND 
	isset($_POST['hikeDifficultyFromAToB']) AND 
	isset($_POST['hikeDifficultyFromAToBToA']) AND 
	isset($_POST['hikeDifficultyFromBToA'])

){
	// the user wants to add the hike to the database. the input will be validated first.

	// get form data
	$hike_name = htmlentities($_POST['hike_name'], ENT_QUOTES);
	$place_name_a = htmlentities($_POST['place_name_a'], ENT_QUOTES);
	$place_name_b = htmlentities($_POST['place_name_b'], ENT_QUOTES);
	$hike_description = htmlentities($_POST['hike_description'], ENT_QUOTES);
	$pathLength = htmlentities($_POST['pathLength'], ENT_QUOTES);
	$pathMaxElevation = htmlentities($_POST['pathMaxElevation'], ENT_QUOTES);
	$pathMinElevation = htmlentities($_POST['pathMinElevation'], ENT_QUOTES);
	$pathElevationDifference = htmlentities($_POST['pathElevationDifference'], ENT_QUOTES);
	$pathElevationIncrease = htmlentities($_POST['pathElevationIncrease'], ENT_QUOTES);
	$pathElevationDecrease = htmlentities($_POST['pathElevationDecrease'], ENT_QUOTES);
	$pathForMySQL = htmlentities($_POST['pathForMySQL'], ENT_QUOTES);
	$placePointAForMySQL = htmlentities($_POST['placePointAForMySQL'], ENT_QUOTES);
	$placePointBForMySQL = htmlentities($_POST['placePointBForMySQL'], ENT_QUOTES);
	$elevationProfileDataForMySQL = htmlentities($_POST['elevationProfileDataForMySQL'], ENT_QUOTES);
	$estimatedDurationAtoB = htmlentities($_POST['estimatedDurationAtoB'], ENT_QUOTES);
	$estimatedDurationBtoA = htmlentities($_POST['estimatedDurationBtoA'], ENT_QUOTES);
	$hikeDifficultyFromAToB = htmlentities($_POST['hikeDifficultyFromAToB'], ENT_QUOTES);
	$hikeDifficultyFromAToBToA = htmlentities($_POST['hikeDifficultyFromAToBToA'], ENT_QUOTES);
	$hikeDifficultyFromBToA = htmlentities($_POST['hikeDifficultyFromBToA'], ENT_QUOTES);

	//TODO: validate

	//if validation ok, then insert in db

	//find id for place A and B in db, and if not, insert the new places
	$radiusInKilometers = 5;
	$placeAId = false;
	$placeBId = false;
	$hikeId = false;

	$findPlaceAInDB = "SELECT id, name, DISTANCE( point, ". $placePointAForMySQL ." ) AS distance
FROM  `place` 
WHERE UPPER( name ) = UPPER(  '". $place_name_a ."' ) 
HAVING distance < ". $radiusInKilometers ."
ORDER BY distance
LIMIT 1;";

	$findPlaceBInDB = "SELECT id, name, DISTANCE( point, ". $placePointBForMySQL ." ) AS distance
FROM  `place` 
WHERE UPPER( name ) = UPPER(  '". $place_name_b ."' ) 
HAVING distance < ". $radiusInKilometers ."
ORDER BY distance
LIMIT 1;";

	if ($placeAInDB = $mysqli->query($findPlaceAInDB)) {

		// if there are any results
		if ($placeAInDB->num_rows > 0) {

			// get place id
			$row = $placeAInDB->fetch_object();
			$placeAId = $row->id;
			$placeAInDB->close();
		}
		else {

			// insert a new place in db and get place id
			$insertPlaceInDB = "INSERT INTO  `vestforskautograd`.`place` (
 `id` ,
 `name` ,
 `point` ,
 `yrId`
)
VALUES (
NULL ,  '". $place_name_a ."', GEOMFROMTEXT(  '". str_replace(", ", " ", $placePointAForMySQL) ."' ) ,  NULL
);";

			if ($mysqli->query($insertPlaceInDB)) {
				$placeAId = $mysqli->insert_id; // id of inserted row
			}
			else {
				echo "ERROR: Could not prepare SQL statement.";
			}

		} //END else
	}
	else {
		echo "ERROR: Could not prepare SQL statement.";
	}

	if ($placeBInDB = $mysqli->query($findPlaceBInDB)) {

		// if there are any results
		if ($placeBInDB->num_rows > 0) {

			$row = $placeBInDB->fetch_object();
			$placeBId = $row->id;
			$placeBInDB->close();
		}
		else {
			// insert a new place in db
			$insertPlaceInDB = "INSERT INTO  `vestforskautograd`.`place` (
 `id` ,
 `name` ,
 `point` ,
 `yrId`
)
VALUES (
NULL ,  '". $place_name_b ."', GEOMFROMTEXT(  '". str_replace(", ", " ", $placePointBForMySQL) ."' ) ,  NULL
);";

			if ($mysqli->query($insertPlaceInDB)) {
				$placeBId = $mysqli->insert_id; // id of inserted row
			}
			else {
				echo "ERROR: Could not prepare SQL statement.";
			}

		} //END else
	}
	else {
		echo "ERROR: Could not prepare SQL statement.";
	}


	$insertHikeInDB = "INSERT INTO  `vestforskautograd`.`hike` (
`id` ,
`name` ,
`path` ,
`description` ,
`placeStart` ,
`placeEnd` ,
`pathLength` ,
`pathMaxElevation` ,
`pathMinElevation` ,
`pathElevationDifference` ,
`pathElevationIncrease` ,
`pathElevationDecrease` ,
`estimatedDurationAtoB` ,
`estimatedDurationBtoA` ,
`elevationProfileData` ,
`hikeDifficultyFromAToB` ,
`hikeDifficultyFromAToBToA` ,
`hikeDifficultyFromBToA`
)
VALUES (
NULL ,  
'". $hike_name ."' , 
GEOMFROMTEXT(  '". $pathForMySQL ."' ) ,  
'". $hike_description ."',  
'". $placeAId ."',  
'". $placeBId ."',  
'". $pathLength ."',  
'". $pathMaxElevation ."',  
'". $pathMinElevation ."',  
'". $pathElevationDifference ."',  
'". $pathElevationIncrease ."',  
'". $pathElevationDecrease ."',  
'". $estimatedDurationAtoB ."', 
'". $estimatedDurationBtoA ."',   
'". $elevationProfileDataForMySQL ."',  
'". $hikeDifficultyFromAToB ."', 
'". $hikeDifficultyFromAToBToA ."',   
'". $hikeDifficultyFromBToA ."'
);";

	if ($mysqli->query($insertHikeInDB)) {
		$hikeId = $mysqli->insert_id; // id of inserted row
	}
	else {
		echo "ERROR: Could not prepare SQL statement.";
	}

	// redirec the user
	header("Location: view.php?hike_id=". $hikeId);

}
else {
	echo "FAIL!";
}

function renderEditPage($jsArray = '', $last ='', $error = '', $id = '') { ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit path</title>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
<!--[if lte IE 8]><link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" /><![endif]-->
<link rel="stylesheet" href="js/leaflet-label/dist/leaflet.label.css">

<script type="text/javascript" charset="UTF-8" src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" charset="UTF-8" src="js/leaflet-label/dist/leaflet.label.js"></script>

<script type="text/javascript" charset="UTF-8" src="js/map_leaflet.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/variables.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/update_boxes.js"></script>

<script type="text/javascript" charset="UTF-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/jquery.flot.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/jquery.flot.crosshair.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/jquery.flot.axislabels.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/proj4js-combined.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/elevation_profile.js"></script>

<script>
<?php
	echo "var pathPolyline = ". $jsArray . ";\n";
?>

var leafletPath = [];
var defaultHikeName = "Enter a hike name *";
var defaultPlaceNameA = "Enter place name A *";
var defaultPlaceNameB = "Enter place name B *";
var hikeDefaultDescription = "Enter a description *";
var hikeName = '';
var hikeDescription = '';
var placeNameA = '';
var placeNameB = '';

function initialize() {
	initialize_vars(pathPolyline);
	initialize_map();
	
/*
	console.debug(pathPolyline);
	console.debug(googleDecodedPath);
	console.debug(googleEncodedPath);
	console.debug(pathLength);
	console.debug(googleElevationForLocations);
*/
	// update HTML
	generateAndDisplayElevationProfile();

} //END initialize()
</script>
</head>

<body onload="initialize()">
<div class="page">
	<div id="page-top">
		<div id="map-div">
		</div><!-- END map-div -->
	</div><!-- END page-top -->
	<div class="page-body">
		<div class="page-left-column">
			<div class="box">
				<div class="content">
				<form id="form_add_hike" action="" method="POST">
					<label for=hike_name>Name of hike</label>
					<input id=hike_name name=hike_name type=text placeholder="Name of hike" oninput="updateHikeName(this);" required autofocus>
					<label for=place_name_a>Name of place A</label>
					<input id=place_name_a name=place_name_a type=text placeholder="Name of place A" oninput="updatePlaceNameA(this);" required>
					<label for=place_name_b>Name of place B</label>
					<input id=place_name_b name=place_name_b type=text placeholder="Name of place B" oninput="updatePlaceNameB(this);" required>
					<label for=hike_description>Description</label>
					<textarea id=hike_description name=hike_description rows=5 oninput="updateHikeDescription(this);" required></textarea>
					<input id="submitButton" type="submit" value="Add hike">
				</form>
				</div>
			</div>
			<div class="box">
				<div id="hike-fact-box" class="content">
					<h1>h1</h1>
					<p>description</p>
					<p>Length: * meters</p>
					<p>Maximum elevation: * meters</p>
					<p>Minimum elevation: * meters</p>
					<p>Difference in elevation: * meters</p>
				</div>
			</div>
		</div><!-- END page-left-column -->

		<div class="page-middle-column">
			<div id="elevationProfile"></div>
		</div><!-- END page-middle-column -->

		<div class="page-middle-column">
			<div id="hike-path-fact-box" class="content">
				<ul class="place-list">
					<li>
						<div id="hike-a-to-b">
							<div id="hike-a-to-b-rate-icon"></div>
							<div id="hike-a-to-b-info">
								<p>A - B</p>
								<p>Uphill: * meters | Downhill: * meters | Estimated duration: *</p>
							</div>
						</div>
					</li>
					<li>
						<div id="hike-b-to-a">
							<div id="hike-b-to-a-rate-icon"></div>
							<div id="hike-b-to-a-info">
								<p>B - A</p>
								<p>Uphill: * meters | Downhill: * meters | Estimated duration: *</p>
							</div>
						</div>
					</li>
					<li>
						<div id="hike-a-to-b-to-a">
							<div id="hike-a-to-b-to-a-rate-icon"></div>
							<div id="hike-a-to-b-to-b-info">
								<p>A - B - A</p>
								<p>Estimated duration: *h *m</p>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

	</div><!-- END page-body -->
	<div class="page-bottom">
		<p>Vestlandsforsking | <a href="index.php">Framside</a></p>
	</div><!-- END page-bottom -->
</div><!-- END page -->

</body>
</html>
<?php }


?>