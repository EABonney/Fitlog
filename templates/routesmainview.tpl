<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.23.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlsn="http://www.w3.org/1999/xhtml">
	<head>
		<title>Fitlog Training</title>
		<meta http-equiv="Content-Type"
		    content="text/html; charset=utf-8"/>
	<script src="scripts/fitlog_scripts.js" type="text/javascript"></script>
	<link href="style1.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
	  <div id="header">
	    <div id="sitebranding">
		<h1>Fitlog</h1>
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
	  <div id="newbutton">
		<table>
		<tr><td><label for="createnew">Create New Route</label></td></tr>
		<tr><td><input type="button" value="New" onclick="getUserAddress()"/></td></tr>
		</table>
	  </div>
	  </div> <!-- end of navigation div -->
	  <div id="bodycontent">
		<h2>Routes</h2>
		<table class="events">
			<tr><th>Description</th><th>Type</th><th>Distance</th><th>Created</th><th>Edit</th><th>Delete</th></tr>
	<!-- BEGIN COURSELISTING -->
			<tr><TD>{DESC}</TD><td>{TYPE}</td><td>{DIST}</td><td>{CREATED}</td><td>{EDIT}</td><td>{DEL}</td>
			</tr>
	<!-- END COURSELISTING -->
		</table>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>