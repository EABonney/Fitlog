<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.23.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlsn="http://www.w3.org/1999/xhtml">
	<head>
		<title>Fitlog Training</title>
		<meta http-equiv="Content-Type"
		    content="text/html; charset=utf-8"/>
	<script src="scripts/xmlhttp.js" type="text/javascript"></script>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAb9KpfEQqY_1OLDaRe5pophSRVq6TI5HwO14ly68M86soTMgp0BStrFgKO8Vvm_eLhAZQ6S8_MeNRMw" type="text/javascript"></script>
	<script src="scripts/map_functions.js" type="text/javascript"></script>
	<link href="style1.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
	  <div id="header">
	    <div id="sitebranding">
		<h1>Fitlog</h1>
		{MONTH}
	    </div>
	    <div id="tagline">
		<p>Track all your fitness training needs</p>	
	    </div>
	  </div>  <!-- end of header div -->
	  <div id="navigation">
		<ul>
		  <li><a href="dashboard.php">Home</a></li>
		  <li><a href="settings.php">Settings</a></li>
		  <li><a href="reports.php">Reports</a></li>
		  <li><a href="workoutview.php">Work Outs</a></li>
		  <li><a href="routes.php">Routes</a></li>
		  <li><a href="trainingplans.php">Training Plans</a></li>
		  <li><a href="downloads.php">Downloads</a></li>
		  <li><a href="about.php">About Fitness Log</a></li>
		  <li><a href="logout.php">Log Out</a></li>
		</ul>
	  </div> <!-- end of navigation div -->
	  <div id="routesummary" class="routesummary">
		<fieldset>
		<legend>Distance</legend>
		<div><label for="miles" id="miles" class="fixedwidth">Mi:</label>
		<input type="text" disabled="true" class="routeinput" /></div>
		<div><label for="kilometers" id="kilometers" class="fixedwidth">Km:</label>
		<input type="text" disabled="true" class="routeinput" /></div>
		<div><label for="routename" id="routename" class="fixedwidth">Name:</label>
		<input type="name" id="name" class="routeinput" /></div>
		<div><input type="button" id="save" value="Save" /></div>
		</fieldset>
	  </div>
	  <div id="bodycontent">
		<h2>Routes</h2>
		<div id="map"></div>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>