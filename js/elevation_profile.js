// Elevation profile - code from kresendo.no

var graphData = new Array();

function generateAndDisplayElevationProfile(){

	var input = JSON.stringify(pathInGeoJSON);

	//var input = "{\"type\":\"LineString\",\"coordinates\":[[7.1607787,61.2706935],[7.1606922,61.2707342],[7.1604417,61.2709188],[7.1603827,61.2709884],[7.1601574,61.2712488],[7.1600876,61.2714293],[7.1600501,61.2715737],[7.1600175,61.2718034],[7.1598784,61.2720868],[7.1597011,61.2725462],[7.1595862,61.2727709],[7.1594063,61.2729815],[7.1594413,61.2730944],[7.1595025,61.2731767],[7.159578,61.2733425],[7.1596477,61.2733966],[7.159713,61.2734736],[7.1597658,61.2735358],[7.1598409,61.2736673],[7.1598887,61.2738204],[7.1599531,61.2739491],[7.1599746,61.2740135],[7.1600207,61.2740977],[7.1600593,61.2741968],[7.1601252,61.2742371],[7.1602141,61.2743157],[7.1603335,61.2744138],[7.1606419,61.2746666],[7.1608583,61.2749962],[7.16088,61.27517],[7.1608527,61.2754095],[7.1607829,61.2756254],[7.1606338,61.2757804],[7.1604415,61.2759026],[7.1600048,61.2761043],[7.1598972,61.2762697],[7.1606788,61.2763033],[7.1611072,61.2763397],[7.1614701,61.2763615],[7.1614765,61.2764251],[7.1611844,61.276511],[7.1611635,61.2766327],[7.1614419,61.2767757],[7.161732,61.2768905],[7.1620057,61.2769965],[7.1621517,61.2770531],[7.162329,61.2770883],[7.1625322,61.2771283],[7.1626542,61.2771485],[7.1627495,61.2771695],[7.1628442,61.2771996],[7.1629616,61.2772052],[7.1636581,61.2772341],[7.1640423,61.2772133],[7.1643924,61.2771625],[7.1647166,61.2771634],[7.1648534,61.2771841],[7.1651018,61.2772314],[7.1653593,61.2773577],[7.16548,61.2774015],[7.1657809,61.2774817],[7.166123,61.2776233],[7.1661753,61.2777233],[7.1662046,61.2778242],[7.1662607,61.2779133],[7.166325,61.2780038],[7.1664536,61.278113],[7.1665609,61.2783244],[7.1666113,61.2784985],[7.1666905,61.2786485],[7.167186,61.2790732],[7.1678096,61.2795324],[7.1679527,61.2796789],[7.1680253,61.2797342],[7.168106,61.2797322],[7.1681464,61.2796479],[7.1681545,61.2795916],[7.1682473,61.2795509],[7.1683281,61.2795741],[7.1684169,61.2796925],[7.1685582,61.2799],[7.1686369,61.2800562],[7.1688347,61.2802598],[7.1689273,61.2804153],[7.168953,61.2805161],[7.1690714,61.2805278]]}";

	$.ajax({
	  type: "POST",
	  url: "getHikeFacts.php",
	  data: { geojson: input }
	}).done(function(data) {

		var result = JSON.parse(data);
		console.debug(result);

		var decodedJsonResult = JSON.stringify(result.jsonResult);
		setElevationProfile(decodedJsonResult);

		setVariables(result.jsonObjectWithHikeFacts);

		updateHikeFactBox();
		updateHikePathFactBox();
		updateFormFacts(decodedJsonResult);

	});


} // END generateAndDisplayElevationProfile()


function setVariables(factObject){

	pathLength = factObject.hikeDistance;
	pathMaxElevation = factObject.hikeMaxElevation;
	pathMinElevation = factObject.hikeMinElevation;
	pathElevationDifference = factObject.elevationDifference;
	pathElevationIncrease = factObject.elevationUphill;
	pathElevationDecrease = factObject.elevationDownhill;
	estimatedDurationAtoB = factObject.hikeDurationFromAToB;
	estimatedDurationBtoA = factObject.hikeDurationFromBToA;
	estimatedDurationAtoBtoA = factObject.hikeDurationFromAToBToA;
	hikeDifficultyFromAToB = factObject.hikeDifficultyFromAToB;
	hikeDifficultyFromAToBToA = factObject.hikeDifficultyFromAToBToA;
	hikeDifficultyFromBToA = factObject.hikeDifficultyFromBToA;

} //SET setVariables()


function setElevationProfile(jsonString) {
	var data = jQuery.parseJSON( jsonString );
	var geoJson = data.features;

	if ( geoJson.length == undefined ){
		geoJson = $.map(geoJson, function (value, key) { return value; });
	}

	// Go through each point
	$.each(geoJson, function(index, value) { 
		elev = value.properties.elev;
		// Check for negative values. The graph does not draw null values
		if(elev < 0) { 
			elev = null;
		}
		tmp = [value.properties.distance, elev];
		graphData.push(tmp);
	});

	/*
		Create ticks according to length of route.
		- routeLenght defines length of route in meter  
		- graphStep defines how often x-ticks is set on the graph.
		If graphStep=2, there is 2km between each x-tick on the graph. 
		Just change the if/else if you want different x-ticks
	*/

	var routeLength = geoJson[geoJson.length-1].properties.distance;
	var graphStep;
	if(routeLength<1001)
		graphStep = 0.2;
	else if(routeLength<2001)
		graphStep = 0.5;
	else if(routeLength > 2000 && routeLength<=4000)
		graphStep = 1;
	else if(routeLength > 4000 && routeLength<6000)
		graphStep = 2;
	else
		graphStep = 4;
	steps = 0
	locSteps = 0
	var xTicks = new Array();
	while(locSteps<routeLength) {
		steps = steps + graphStep;
		locSteps = locSteps + graphStep*1000;
		xTicks.push([locSteps, steps + ' km']);
	}
				
	// Add plot to DOM
	plot = $.plot($("#elevationProfile"),
		[ { data: graphData, color: 'blue'}], {
			xaxes: [{/*axisLabel: "Avstand"*/}],
			yaxes: [{/*axisLabel: "H&oslash;yde"*/}],
			xaxis: {
				show: true,
				ticks: xTicks
			},
			series: {
				lines: { show: true },
				points: { show: false }
			},
			crosshair: { mode: "x" },
			grid: { hoverable: true, autoHighlight: false },
		});

	$("#elevationProfile").bind("plothover",  function (event, pos, item) {
		updatePointInMap(geoJson, pos, plot);
	}); 
	
} // END setElevationProfile()


/*
** Show point in map when user hovers elevation profile
*/
function updatePointInMap(geoJson, pos, plot) {
	markerGroup.clearLayers();
    
    var axes = plot.getAxes();
    if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
        pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
        return;

    var i, j, dataset = plot.getData();
    for (i = 0; i < dataset.length; ++i) {
        var series = dataset[i];

        // find the nearest points, x-wise
        for (j = 0; j < series.data.length; ++j)
            if (series.data[j][0] > pos.x) 
                break;
        
        // now interpolate
        var y, p1 = series.data[j - 1], p2 = series.data[j];
        if (p1 == null)
            y = p2[1];
        else if (p2 == null)
            y = p1[1];
        else
            y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);
    }
	
	
	var source = new Proj4js.Proj('EPSG:900913');    //source coordinates will be in Longitude/Latitude
	var dest = new Proj4js.Proj('EPSG:4326');     //destination coordinates in LCC, south of France
	var p = new Proj4js.Point(geoJson[j].geometry.coordinates[0], geoJson[j].geometry.coordinates[1]);   //any object will do as long as it has 'x' and 'y' properties

	Proj4js.transform(source, dest, p);      //do the transformation.  x and y are modified in place

	
	var latlng = new L.LatLng(p.y, p.x);
	marker = new L.CircleMarker(latlng);
	markerGroup.addLayer(marker);
        
} //END updatePointInMap()