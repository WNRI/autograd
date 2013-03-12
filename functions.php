<?php

//Example: $gpxFilePath = 'olavskjelda.gpx';
function getLatLngFromGpxFile($gpxFilePath){
	$xml = simplexml_load_file($gpxFilePath);
	$xml->registerXPathNamespace('gpx', 'http://www.topografix.com/GPX/1/1');
	return $res = $xml->xpath('//gpx:trkpt');
} //END getLatLngFromGpxFile()


// clean output from getLatLngFromGpxFile()
function cleanLatLngFromGpxFile($dirtyInput){
	
	$cleanOutput = array();

	foreach($dirtyInput as $key => $object)
	{
		$cleanLatLng = array((string) $object->attributes()->lat,(string) $object->attributes()->lon);
		$cleanOutput[] = $cleanLatLng;
	}
	
	return $cleanOutput;

} //END cleanLatLngFromGpxFile()


//TEST getLatLngFromGpxFile() and cleanLatLngFromGpxFile()
/*
$gpxFile = 'olavskjelda.gpx';
$gpxPath = getLatLngFromGpxFile($gpxFile);
echo print_r(cleanLatLngFromGpxFile($gpxPath));
*/


function createJavaScriptPolylineFromGpxFile($gpxFilePath){

	$gpxPath = getLatLngFromGpxFile($gpxFilePath);
	$phpArray = cleanLatLngFromGpxFile($gpxPath);
	$jsArray = json_encode($phpArray);

	return $jsArray;

} //END createJavaScriptPolylineFromGpxFile()


//TEST createJavaScriptPolylineFromGpxFile()
/*
$gpxFile = 'olavskjelda.gpx';
echo print_r(createJavaScriptPolylineFromGpxFile($gpxFile));
*/


//Statkart tenesta var nede no den 05.02.2013 så eg har sendt dei ein mail om det.
//http://localhost/vestforsk_autograd/request_statkart_profile.php?gpx_file_url=http://sognefjord.vestforsk.no/test1.gpx
function getStatkartProfile($gpxFilePath){
	$postdata = http_build_query(
	    array('gpx_file_url' => $gpxFilePath)
	);

	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);

	$context  = stream_context_create($opts);

	return $result = file_get_contents('request_statkart_profile.php', false, $context);
} //END getStatkartProfile()




function listPathInArray($linestring){

	$string = str_replace(array("LINESTRING(",")"),array("",""),$linestring);
	$arrayA = explode(",", $string);
	$arrayB = array();

	for($i = 0, $size = count($arrayA); $i < $size; ++$i) {
		$arrayC = explode(" ", $arrayA[$i]);
		$arrayB[$i]['lat'] = (float) $arrayC[0];
		$arrayB[$i]['lng'] = (float) $arrayC[1];
	}

	return $arrayB;

} //END listPathInArray()

//test
//print_r(listPathInArray("LINESTRING(61.2706935 7.1607787,61.2707342 7.1606922,61.2709188 7.1604417,61.2709884 7.1603827,61.2712488 7.1601574,61.2714293 7.1600876,61.2715737 7.1600501,61.2718034 7.1600175,61.2720868 7.1598784,61.2725462 7.1597011,61.2727709 7.1595862,61.2729815 7.1594063,61.2730944 7.1594413,61.2731767 7.1595025,61.2733425 7.159578,61.2733966 7.1596477,61.2734736 7.159713,61.2735358 7.1597658,61.2736673 7.1598409,61.2738204 7.1598887,61.2739491 7.1599531,61.2740135 7.1599746,61.2740977 7.1600207,61.2741968 7.1600593,61.2742371 7.1601252,61.2743157 7.1602141,61.2744138 7.1603335,61.2746666 7.1606419,61.2749962 7.1608583,61.27517 7.16088,61.2754095 7.1608527,61.2756254 7.1607829,61.2757804 7.1606338,61.2759026 7.1604415,61.2761043 7.1600048,61.2762697 7.1598972,61.2763033 7.1606788,61.2763397 7.1611072,61.2763615 7.1614701,61.2764251 7.1614765,61.276511 7.1611844,61.2766327 7.1611635,61.2767757 7.1614419,61.2768905 7.161732,61.2769965 7.1620057,61.2770531 7.1621517,61.2770883 7.162329,61.2771283 7.1625322,61.2771485 7.1626542,61.2771695 7.1627495,61.2771996 7.1628442,61.2772052 7.1629616,61.2772341 7.1636581,61.2772133 7.1640423,61.2771625 7.1643924,61.2771634 7.1647166,61.2771841 7.1648534,61.2772314 7.1651018,61.2773577 7.1653593,61.2774015 7.16548,61.2774817 7.1657809,61.2776233 7.166123,61.2777233 7.1661753,61.2778242 7.1662046,61.2779133 7.1662607,61.2780038 7.166325,61.278113 7.1664536,61.2783244 7.1665609,61.2784985 7.1666113,61.2786485 7.1666905,61.2790732 7.167186,61.2795324 7.1678096,61.2796789 7.1679527,61.2797342 7.1680253,61.2797322 7.168106,61.2796479 7.1681464,61.2795916 7.1681545,61.2795509 7.1682473,61.2795741 7.1683281,61.2796925 7.1684169,61.2799 7.1685582,61.2800562 7.1686369,61.2802598 7.1688347,61.2804153 7.1689273,61.2805161 7.168953,61.2805278 7.1690714)"));


function requestDataFromTurkompisen($jsonDataInput){

	$url = 'http://www.turkompisen.no/elevProfile/elevationprofile.json';

	// Initializing curl
	$ch = curl_init( $url );
 
	// Configuring curl options
	$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
		CURLOPT_POSTFIELDS => $jsonDataInput
	);
 
	// Setting curl options
	curl_setopt_array( $ch, $options );
 
	// Getting results
	$jsonResult =  curl_exec($ch);
	curl_close($ch);

	return $jsonResult;

} //END requestDataFromTurkompisen()

//test
//$data = "{\"type\":\"LineString\",\"coordinates\":[[7.1607787,61.2706935],[7.1606922,61.2707342],[7.1604417,61.2709188],[7.1603827,61.2709884],[7.1601574,61.2712488],[7.1600876,61.2714293],[7.1600501,61.2715737],[7.1600175,61.2718034],[7.1598784,61.2720868],[7.1597011,61.2725462],[7.1595862,61.2727709],[7.1594063,61.2729815],[7.1594413,61.2730944],[7.1595025,61.2731767],[7.159578,61.2733425],[7.1596477,61.2733966],[7.159713,61.2734736],[7.1597658,61.2735358],[7.1598409,61.2736673],[7.1598887,61.2738204],[7.1599531,61.2739491],[7.1599746,61.2740135],[7.1600207,61.2740977],[7.1600593,61.2741968],[7.1601252,61.2742371],[7.1602141,61.2743157],[7.1603335,61.2744138],[7.1606419,61.2746666],[7.1608583,61.2749962],[7.16088,61.27517],[7.1608527,61.2754095],[7.1607829,61.2756254],[7.1606338,61.2757804],[7.1604415,61.2759026],[7.1600048,61.2761043],[7.1598972,61.2762697],[7.1606788,61.2763033],[7.1611072,61.2763397],[7.1614701,61.2763615],[7.1614765,61.2764251],[7.1611844,61.276511],[7.1611635,61.2766327],[7.1614419,61.2767757],[7.161732,61.2768905],[7.1620057,61.2769965],[7.1621517,61.2770531],[7.162329,61.2770883],[7.1625322,61.2771283],[7.1626542,61.2771485],[7.1627495,61.2771695],[7.1628442,61.2771996],[7.1629616,61.2772052],[7.1636581,61.2772341],[7.1640423,61.2772133],[7.1643924,61.2771625],[7.1647166,61.2771634],[7.1648534,61.2771841],[7.1651018,61.2772314],[7.1653593,61.2773577],[7.16548,61.2774015],[7.1657809,61.2774817],[7.166123,61.2776233],[7.1661753,61.2777233],[7.1662046,61.2778242],[7.1662607,61.2779133],[7.166325,61.2780038],[7.1664536,61.278113],[7.1665609,61.2783244],[7.1666113,61.2784985],[7.1666905,61.2786485],[7.167186,61.2790732],[7.1678096,61.2795324],[7.1679527,61.2796789],[7.1680253,61.2797342],[7.168106,61.2797322],[7.1681464,61.2796479],[7.1681545,61.2795916],[7.1682473,61.2795509],[7.1683281,61.2795741],[7.1684169,61.2796925],[7.1685582,61.2799],[7.1686369,61.2800562],[7.1688347,61.2802598],[7.1689273,61.2804153],[7.168953,61.2805161],[7.1690714,61.2805278]]}";
//print_r(requestDataFromTurkompisen($data));


function handleDataFromTurkompisen($jsonInput){

	$phpInput = json_decode($jsonInput);
	$data = $phpInput->features;

	$phpInputArrayLength = count($data) - 1;

	// har svaret på
	$hikeDistance = $data[$phpInputArrayLength]->properties->distance;

	// finn svar gjennom for løkka
	$hikeMaxElevation = $data[0]->properties->elev;
	$hikeMinElevation = $data[0]->properties->elev;
	$elevationUphill = 0;
	$elevationDownhill = 0;

	// for løkke
	$previousElevation = $data[0]->properties->elev;

	// hoppar over første point fordi den ikkje har eit previous point, derfor $i = 1
	for($i = 1, $size = count($data); $i < $size; ++$i) {

		$currentElevation = $data[$i]->properties->elev;
		$elevationDifferenceFromLastPoint = $currentElevation - $previousElevation;


		if ( $currentElevation > $previousElevation ) {

			//uphill
			$elevationUphill += $elevationDifferenceFromLastPoint;

		} else {

			//downhill
			$elevationDownhill += $elevationDifferenceFromLastPoint;

		} //END else

		if ( $currentElevation > $hikeMaxElevation ) {
			$hikeMaxElevation = $currentElevation;
		}

		if ( $currentElevation < $hikeMinElevation ) {
			$hikeMinElevation = $currentElevation;
		}

		$previousElevation = $currentElevation;
		
	} //END for

	// finn svaret på etter løkka
	$elevationDifference = $hikeMaxElevation - $hikeMinElevation;

	$hikeDurationFromAToB = calculateDuration($elevationUphill,$hikeDistance);
	$hikeDurationFromBToA = calculateDuration($elevationDownhill,$hikeDistance);
	$hikeDifficultyFromAToB = calculateDifficulty($hikeDurationFromAToB,$elevationUphill);
	$hikeDifficultyFromBToA = calculateDifficulty($hikeDurationFromBToA,abs($elevationDownhill));
	$hikeDurationFromAToBToA = $hikeDurationFromAToB + $hikeDurationFromBToA;
	$elevationUphillAToBToA = $elevationUphill + abs($elevationDownhill);
	$hikeDifficultyFromAToBToA = calculateDifficulty($hikeDurationFromAToBToA,$elevationUphillAToBToA);

	//return result in object
	$jsonObject = json_encode(
		array(
			'hikeDistance' => $hikeDistance,
			'hikeMaxElevation' => $hikeMaxElevation,
			'hikeMinElevation' => $hikeMinElevation,
			'elevationDifference' => $elevationDifference,
			'elevationUphill' => $elevationUphill,
			'elevationDownhill' => abs($elevationDownhill),
			'hikeDurationFromAToB' => $hikeDurationFromAToB,
			'hikeDurationFromBToA' => $hikeDurationFromBToA,
			'hikeDifficultyFromAToB' => $hikeDifficultyFromAToB,
			'hikeDifficultyFromBToA' => $hikeDifficultyFromBToA,
			'hikeDurationFromAToBToA' => $hikeDurationFromAToBToA,
			'hikeDifficultyFromAToBToA' => $hikeDifficultyFromAToBToA
			),
		JSON_FORCE_OBJECT
	);

	return $jsonObject;

} //END handleDataFromTurkompisen()


function calculateDuration(
	$elevationUphill,
	$hikeDistance
){
	// rekne ut duration, frå a til b
	$a = abs($elevationUphill) / 300;
	$b = $hikeDistance / 2000;
	$hikeDuration = $a + $b;

	return $hikeDuration;
} //END calculateDuration()


function calculateDifficulty(
	$hikeDuration,
	$elevationUphill
){
	$hikeDifficulty = "N/A";

	if ( $hikeDuration < 3 && $elevationUphill < 300 ) {
		$hikeDifficulty = "Easy";
	}
	else if ( $hikeDuration < 6 && $elevationUphill < 400 ) {
		$hikeDifficulty = "Medium";
	}
	else if ( $hikeDuration < 9 && $elevationUphill < 1000 ) {
		$hikeDifficulty = "Hard";
	}
	else if ( $elevationUphill > 1000 ) {
		$hikeDifficulty = "Extreme";
	}

	return $hikeDifficulty;
} //END calculateDifficulty()

?>