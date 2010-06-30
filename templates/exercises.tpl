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
			<ul>
				<li><a href="strengthview.php">Strength</a></li>
				<li><a href="exercises.php">Exercises</a></li>
				<li>Other</li>		
			</ul>
		  <li><a href="routes.php">Routes</a></li>
		  <li><a href="trainingplans.php">Training Plans</a></li>
		  <li><a href="downloads.php">Downloads</a></li>
		  <li><a href="about.php">About Fitlog</a></li>
		  <li><a href="logout.php">Log Out</a></li>
		</ul>
	  </div> <!-- end of navigation div -->
	  <div id="bodycontent">
	<h2>Exercises</h2>
	<div id="exercise_entry">
	<!-- BEGIN INPUT -->
		<table>
		<form action="ftlogexercisestype.php" method="post">
		<tr><td width="300">
		<label for="exercise_category">Exercise Category:</label>
		<input type="text" name="exercise_category" id="exercise_category" value="{EXERCISECAT}" TABINDEX=1/>
		</td></tr>
		<tr><td>
		<label for="exercise_desc">Exercise description:</label>
		<input type="text" name="exercise_desc" id="exercise_desc" value="{EXERCISEDESC}" TABINDEX=2/>
		</td></tr>
		<input type="hidden" name="exercise_type_id" id="exercise_type_id" value="{EXERCISETYPEID}" />
		<input type="hidden" name="updating" id="updating" value="{UPDATING}" />
		<tr><td>
		<input type="submit" value="Save Exercise" />
		</td></tr>
		</form>
		</table>
	<!-- END INPUT -->
	</div>
	<div id="exercise_list">
		<table class="events">
		<tr><th width=25>Category</th><th>Description</th><th colspan=2>Modify</th></tr>
	<!-- BEGIN EXERCISELIST -->
		<tr>
		<td>{CATEGORY}</td><td>{DESCRIPTION}</td><td><input type="button" value="edit" onclick="editExercises({ID})"/></td><td><a href="fitlogdelete.php?source=exercise_type&exercise_type_id={EXERCISEID}">delete</a></td>
		</tr>
	<!-- END EXERCISELIST -->
		</table>
	</div>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>