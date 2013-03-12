<?php
if ( isset($_POST['gpx_file_url']) ){
	
	//eksempel: http://openwps.statkart.no/skwms1/wps.elevation?request=Execute&service=WPS&version=1.0.0&identifier=elevationChart&datainputs=[gpx=http://sognefjord.vestforsk.no/test1.gpx]
	$request_url = "http://openwps.statkart.no/skwms1/wps.elevation?request=Execute&service=WPS&version=1.0.0&identifier=elevationChart&datainputs=[gpx=" .
		$_POST['gpx_file_url'] .
		"]";
	
	// Open the Curl session
	$session = curl_init($request_url);

	// Don't return HTTP headers. Do return the contents of the call
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// Make the call
	$xml = curl_exec($session);

	// The web service returns XML. Set the Content-Type appropriately
	header("Content-Type: text/xml");

	echo $xml;
	curl_close($session);
	
} //END if()
?>