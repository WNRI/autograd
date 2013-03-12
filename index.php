<?php

	include_once "connect-db.php";

	if (isset($_POST['emptyDatabase'])) {
		$result = $mysqli->query("DELETE FROM `hike`");
		$result = $mysqli->query("DELETE FROM `place`");
	} //END if

	$hikesInDB = array();

	if ($result = $mysqli->query("SELECT id, name FROM hike")) {

		// if there are any results
		if ($result->num_rows > 0) {

			// for each result
			while ($row = $result->fetch_object()) {

				$hikesInDB[] = $row;
				//echo $row->id;
				//echo $row->name;

			} //END while
		} //END if

	} else { echo "ERROR: Could not prepare SQL statement."; }

	renderViewPage(
		$hikesInDB
	);


function renderViewPage(
	$hikesInDB
) { ?>
<!DOCTYPE html>
<html>
<head>
<title>Autograd</title>
<link rel="stylesheet" href="style-front-page.css" media="screen">
</head>

<body>
<div class="page">
	<div class="page-top">
		<h1>Autograd</h1>
		<p>Presentation text.</p>
	</div>
	<div class="page-body">

		<div class="page-column">
			<h2>Add path from GPX file</h2>
			<div class="content">
				<form action="edit.php" method="POST">
					<input type="file" name="gpx_file_input">
					<input class="button" type="submit" value="Add this path">
				</form>
			</div>
		</div>
		<div class="page-column">
			<h2>Hikes added <?php echo "(". count($hikesInDB) .")"?></h2>
			<div id="hike-list">
<?php
	if (count($hikesInDB) > 0){
		echo "<ul>\n";
		foreach ($hikesInDB as $key => $row) {
			echo "<li><a href='view.php?hike_id=". $row->id ."'>". $row->name ."</a></li>\n";
		}
		echo "</ul>\n";
	} //END if
?>				
			</div>
<?php
	if (count($hikesInDB) > 0){
		echo "<form action='index.php' method='POST'>\n
	<input type='hidden' name='emptyDatabase'>\n
	<input class='button' type='submit' value='Empty example database'>\n
</form>\n";
	} //END if
?>	
		</div>

	</div>
	<div class="page-bottom">
		<p>Vestlandsforskning</p>
	</div>
</div>
</body>
</html>
<?php } //END function renderViewPage()


?>