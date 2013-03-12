<?php

include_once "functions.php";
include_once "connect-db.php";

if (isset($_GET['hike_id'])) {

	$hike_id = htmlentities($_GET['hike_id'], ENT_QUOTES);

	if ($result = $mysqli->query("SELECT *, AsText(path) AS linestring FROM hike WHERE id = ". $hike_id)) {
		if ($result->num_rows > 0){
			
			$row = $result->fetch_object();

			if(
				isset($row->name) AND 
				isset($row->linestring) AND 
				isset($row->elevationProfileData) AND 
				isset($row->placeStart) AND 
				isset($row->placeEnd)
			){
				$hike_name = html_entity_decode($row->name);
				$pathInArray = listPathInArray(html_entity_decode($row->linestring));
				$jsonEncodedPath = json_encode($pathInArray);
				$elevationProfileData = html_entity_decode($row->elevationProfileData);
				$placeNameA = "N/A";
				$placeNameB = "N/A";
				$description = html_entity_decode($row->description);
				$pathLength = html_entity_decode($row->pathLength);
				$pathMaxElevation = html_entity_decode($row->pathMaxElevation);
				$pathMinElevation = html_entity_decode($row->pathMinElevation);
				$pathElevationDifference = html_entity_decode($row->pathElevationDifference);
				$pathElevationIncrease = html_entity_decode($row->pathElevationIncrease);
				$pathElevationDecrease = html_entity_decode($row->pathElevationDecrease);
				$estimatedDurationAtoB = html_entity_decode($row->estimatedDurationAtoB);
				$estimatedDurationBtoA = html_entity_decode($row->estimatedDurationBtoA);
				$hikeDifficultyFromAToB = html_entity_decode($row->hikeDifficultyFromAToB);
				$hikeDifficultyFromAToBToA = html_entity_decode($row->hikeDifficultyFromAToBToA);
				$hikeDifficultyFromBToA = html_entity_decode($row->hikeDifficultyFromBToA);

				if ($result = $mysqli->query("SELECT name FROM place WHERE id = ". $row->placeStart)) {
					if ($result->num_rows > 0){
						$place1 = $result->fetch_object();
						if(isset($place1->name)){
							$placeNameA = html_entity_decode($place1->name);
						} else { echo "Values from db are missing."; }
					} else { echo "No results to display."; }
				} else { echo "ERROR: Could not prepare SQL statement."; }

				if ($result = $mysqli->query("SELECT name FROM place WHERE id = ". $row->placeEnd)) {
					if ($result->num_rows > 0){
						$place2 = $result->fetch_object();
						if(isset($place2->name)){
							$placeNameB = html_entity_decode($place2->name);
						} else { echo "Values from db are missing."; }
					} else { echo "No results to display."; }
				} else { echo "ERROR: Could not prepare SQL statement."; }

				renderViewPage(
					$row->id, //$hike_id
					$hike_name,
					$jsonEncodedPath,
					$elevationProfileData,
					$placeNameA,
					$placeNameB,
					$description,
					$pathLength,
					$pathMaxElevation,
					$pathMinElevation,
					$pathElevationDifference,
					$pathElevationIncrease,
					$pathElevationDecrease,
					$estimatedDurationAtoB,
					$estimatedDurationBtoA,
					$hikeDifficultyFromAToB,
					$hikeDifficultyFromAToBToA,
					$hikeDifficultyFromBToA
				);

			}
			else {
				echo "Values from db are missing.";
			}
		}
		else {
			echo "No results to display.";
		}
	}
	else {
		echo "ERROR: Could not prepare SQL statement.";
	}
}
else {
	echo "hike_id missing";
}

function renderViewPage(
	$hike_id,
	$hike_name,
	$jsonEncodedPath,
	$elevationProfileData,
	$placeNameA,
	$placeNameB,
	$description,
	$pathLength,
	$pathMaxElevation,
	$pathMinElevation,
	$pathElevationDifference,
	$pathElevationIncrease,
	$pathElevationDecrease,
	$estimatedDurationAtoB,
	$estimatedDurationBtoA,
	$hikeDifficultyFromAToB,
	$hikeDifficultyFromAToBToA,
	$hikeDifficultyFromBToA
) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $hike_name; ?></title>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
<!--[if lte IE 8]><link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" /><![endif]-->
<link rel="stylesheet" href="js/leaflet-label/dist/leaflet.label.css">

<script type="text/javascript" charset="UTF-8" src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script type="text/javascript" charset="UTF-8" src="js/leaflet-label/dist/leaflet.label.js"></script>

<script type="text/javascript" charset="UTF-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/jquery.flot.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/jquery.flot.crosshair.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/jquery.flot.axislabels.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/proj4js-combined.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/elevation_profile.js"></script>

<script type="text/javascript" charset="UTF-8" src="js/map_leaflet.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/variables.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/update_boxes.js"></script>

<script>
<?php
	echo "var jsonEncodedPath = ". $jsonEncodedPath .";\n";
	echo "var elevationProfileData = ". $elevationProfileData .";\n";

	echo "var hikeName = '". $hike_name ."';\n";
	echo "var placeNameA = '". $placeNameA ."';\n";
	echo "var placeNameB = '". $placeNameB ."';\n";
	echo "var hikeDescription = '". $description ."';\n";

	echo "var pathLength = '". $pathLength ."';\n";
	echo "var pathMaxElevation = '". $pathMaxElevation ."';\n";
	echo "var pathMinElevation = '". $pathMinElevation ."';\n";
	echo "var pathElevationDifference = '". $pathElevationDifference ."';\n";
	echo "var pathElevationIncrease = '". $pathElevationIncrease ."';\n";
	echo "var pathElevationDecrease = '". $pathElevationDecrease ."';\n";
	echo "var estimatedDurationAtoB = '". $estimatedDurationAtoB ."';\n";
	echo "var estimatedDurationBtoA = '". $estimatedDurationBtoA ."';\n";
	echo "var hikeDifficultyFromAToB = '". $hikeDifficultyFromAToB ."';\n";
	echo "var hikeDifficultyFromAToBToA = '". $hikeDifficultyFromAToBToA ."';\n";
	echo "var hikeDifficultyFromBToA = '". $hikeDifficultyFromBToA ."';\n";	
?>
var leafletPath = [];
var defaultHikeName = "Enter a hike name *";
var hikeDefaultDescription = "Enter a description *";
var defaultPlaceNameA = "Enter a name for place A *";
var defaultPlaceNameB = "Enter a name for place B *";

function initialize() {

	setVarLeafletPath(jsonEncodedPath);
	setElevationProfile(JSON.stringify(elevationProfileData));

	//initialize_vars();
	initialize_map();

/*
	
	// update HTML
	updateHikeNameInFactBox();
	updatePathAToBPlaceNamesInFactBox();
	generateAndDisplayElevationProfile();
*/

} //END initialize()
</script>
</head>

<body onload="initialize()">
<div class="page">
	<div id="page-top">
		<div id="map-div"></div>
	</div><!-- END page-top -->
	<div class="page-body">
		<div class="page-left-column">
			<div class="box">
				<div id="hike-fact-box" class="content">
					<h1><?php echo $hike_name; ?></h1>
					<p><?php echo $description; ?></p>
					<p>Length: <?php echo $pathLength; ?> meters</p>
					<p>Maximum elevation: <?php echo $pathMaxElevation; ?> meters</p>
					<p>Minimum elevation: <?php echo $pathMinElevation; ?> meters</p>
					<p>Difference in elevation: <?php echo $pathElevationDifference; ?> meters</p>
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
<?php } //END function renderViewPage()


?>