


function updateHikePathFactBox(){

	var div = document.getElementById('hike-path-fact-box');
		div.innerHTML = "";

	var ul = document.createElement("ul");
		ul.setAttribute("class", "place-list");

	var li = document.createElement("li");

	var liDiv = document.createElement("div");
		liDiv.id = "hike-a-to-b";

	var iconDiv = document.createElement("div");
		iconDiv.id = "hike-a-to-b-rate-icon";

	var img = document.createElement("img");

	if (hikeDifficultyFromAToB == 'Easy'){
		img.src = "images/green60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromAToB == 'Medium'){
		img.src = "images/blue60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromAToB == 'Hard'){
		img.src = "images/red60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromAToB == 'Extreme'){
		img.src = "images/black60x60.png";
		iconDiv.appendChild(img);
	}

	var factDiv = document.createElement("div");
		factDiv.id = "hike-a-to-b-info";

	var p1 = document.createElement("p");
		p1.innerHTML = getPathName('from a to b');

	var p2 = document.createElement("p");
		p2.innerHTML =  getPathFacts('from a to b');

	liDiv.appendChild(iconDiv);
	factDiv.appendChild(p1);
	factDiv.appendChild(p2);
	liDiv.appendChild(factDiv);
	li.appendChild(liDiv);
	ul.appendChild(li);

	var li = document.createElement("li");

	var liDiv = document.createElement("div");
		liDiv.id = "hike-b-to-a";

	var iconDiv = document.createElement("div");
		iconDiv.id = "hike-b-to-a-rate-icon";

	var img = document.createElement("img");

	if (hikeDifficultyFromBToA == 'Easy'){
		img.src = "images/green60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromBToA == 'Medium'){
		img.src = "images/blue60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromBToA == 'Hard'){
		img.src = "images/red60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromBToA == 'Extreme'){
		img.src = "images/black60x60.png";
		iconDiv.appendChild(img);
	}

	var factDiv = document.createElement("div");
		factDiv.id = "hike-b-to-a-info";

	var p1 = document.createElement("p");
		p1.innerHTML = getPathName('from b to a');

	var p2 = document.createElement("p");
		p2.innerHTML =  getPathFacts('from b to a');

	liDiv.appendChild(iconDiv);
	factDiv.appendChild(p1);
	factDiv.appendChild(p2);
	liDiv.appendChild(factDiv);
	li.appendChild(liDiv);
	ul.appendChild(li);

	var li = document.createElement("li");

	var liDiv = document.createElement("div");
		liDiv.id = "hike-a-to-b-to-a";

	var iconDiv = document.createElement("div");
		iconDiv.id = "hike-a-to-b-to-a-rate-icon";

	var img = document.createElement("img");

	if (hikeDifficultyFromAToBToA == 'Easy'){
		img.src = "images/green60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromAToBToA == 'Medium'){
		img.src = "images/blue60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromAToBToA == 'Hard'){
		img.src = "images/red60x60.png";
		iconDiv.appendChild(img);
	}
	else if (hikeDifficultyFromAToBToA == 'Extreme'){
		img.src = "images/black60x60.png";
		iconDiv.appendChild(img);
	}

	var factDiv = document.createElement("div");
		factDiv.id = "hike-a-to-b-to-b-info";

	var p1 = document.createElement("p");
		p1.innerHTML = getPathName('from a to b to a');

	var p2 = document.createElement("p");
		p2.innerHTML =  getPathFacts('from a to b to a');

	liDiv.appendChild(iconDiv);
	factDiv.appendChild(p1);
	factDiv.appendChild(p2);
	liDiv.appendChild(factDiv);
	li.appendChild(liDiv);
	ul.appendChild(li);

	div.appendChild(ul);

} //END updateHikePathFactBox()


function updateHikeFactBox(){

	var div = document.getElementById('hike-fact-box');
		div.innerHTML = "";

	var h1 = document.createElement("h1");
		h1.innerHTML = getHikeName();

	var p1 = document.createElement("p");
		p1.innerHTML = getHikeDescription();

	var p2 = document.createElement("p");
		p2.innerHTML = "Length: "+ pathLength +" meters";

	var p3 = document.createElement("p");
		p3.innerHTML = "Maximum elevation: "+ Math.round(pathMaxElevation) +" meters";

	var p4 = document.createElement("p");
		p4.innerHTML = "Minimum elevation: "+ Math.round(pathMinElevation) +" meters";

	var p5 = document.createElement("p");
		p5.innerHTML = "Difference in elevation: "+ Math.round(pathElevationDifference) +" meters";

	div.appendChild(h1);
	div.appendChild(p1);
	div.appendChild(p2);
	div.appendChild(p3);
	div.appendChild(p4);
	div.appendChild(p5);

} //END updateHikeFactBox()

function getPathFacts(shape){

	var pathFacts = "N/A";
	var a = Number(estimatedDurationAtoB) + Number(estimatedDurationBtoA);

	if (shape == 'from a to b') {
		pathFacts = "Uphill: "+ Math.round(pathElevationIncrease) +" meters | Downhill: "+ Math.round(pathElevationDecrease) +" meters | Estimated duration: "+ convertTime(Number(estimatedDurationAtoB));
	}
	else if (shape == 'from b to a') {
		pathFacts = "Uphill: "+ Math.round(pathElevationDecrease) +" meters | Downhill: "+ Math.round(pathElevationIncrease) +" meters | Estimated duration: "+ convertTime(Number(estimatedDurationBtoA));
	}
	else if (shape == 'from a to b to a') {
		pathFacts = "Estimated duration: "+ convertTime(Number(a));
	}

	return pathFacts;

} //END getPathFacts()


function getPathName(shape){

	var pathName = "N/A";
	var A = defaultPlaceNameA;
	var B = defaultPlaceNameB;

	if (placeNameA != undefined){
		if (placeNameA.length > 0){
			A = placeNameA;
		}
	}

	if (placeNameB != undefined){
		if (placeNameB.length > 0){
			B = placeNameB;
		}
	}

	if (shape == 'from a to b') {
		pathName = A + " - " + B;
	}
	else if (shape == 'from b to a') {
		pathName = B + " - " + A;
	}
	else if (shape == 'from a to b to a') {
		pathName = A + " - " + B + " - " + A;
	}

	return pathName;

} //END getPathName()


function getHikeName(){

	var H = defaultHikeName;

	if (hikeName != undefined){
		if(hikeName.length > 0 ){
			H = hikeName;
		}
	}
	
	return H;

} //END getHikeName()



function getHikeDescription(){

	var D = hikeDefaultDescription;

	if (hikeDescription != undefined){
		if(hikeDescription.length > 0 ){
			D = hikeDescription;
		}
	}
	
	return D;

} //END getHikeDescription()


function updateFormFacts(decodedJsonResult){

	var form_add_hike = document.getElementById('form_add_hike');

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathLength";
		input.value = pathLength;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathMaxElevation";
		input.value = pathMaxElevation;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathMinElevation";
		input.value = pathMinElevation;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathElevationDifference";
		input.value = pathElevationDifference;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathElevationIncrease";
		input.value = pathElevationIncrease;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathElevationDecrease";
		input.value = pathElevationDecrease;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "pathForMySQL";
		input.value = pathForMySQL;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "placePointAForMySQL";
		input.value = placePointAForMySQL;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "placePointBForMySQL";
		input.value = placePointBForMySQL;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "estimatedDurationAtoB";
		input.value = estimatedDurationAtoB;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "estimatedDurationBtoA";
		input.value = estimatedDurationBtoA;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "hikeDifficultyFromAToB";
		input.value = hikeDifficultyFromAToB;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "hikeDifficultyFromAToBToA";
		input.value = hikeDifficultyFromAToBToA;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "hikeDifficultyFromBToA";
		input.value = hikeDifficultyFromBToA;
	form_add_hike.appendChild(input);

	var input = document.createElement("input");
		input.type = "hidden";
		input.name = "elevationProfileDataForMySQL";
		input.value = decodedJsonResult;
	form_add_hike.appendChild(input);

} //END updateFormFacts()