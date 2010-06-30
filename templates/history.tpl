<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.23.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlsn="http://www.w3.org/1999/xhtml">
	<head>
		<title>Fitlog Training</title>
		<meta http-equiv="Content-Type"
		    content="text/html; charset=utf-8"/>
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
		  <li><a href="history.php">Work Outs</a></li>
		  <li><a href="routes.php">Routes</a></li>
		  <li><a href="trainingplans.php">Training Plans</a></li>
		  <li><a href="about.php">About Fitness Log</a></li>
		  <li><a href="logout.php">Log Out</a></li>
		</ul>
	  </div> <!-- end of navigation div -->
	  <div id="bodycontent">
	<!-- BEGIN NAVIGATION -->
		<h2>{MONTH}</h2>
		<h2><a href="ftnav.php?month={PREVIOUS}&source=summary">Previous</a> 
		    <a href="ftnav.php?source=summary">Current</a> 
		    <a href="ftnav.php?month={NEXT}&source=summary">Next</a></h2>
	<!-- END NAVIGATION -->
		<h2>Swim</h2>
		<table class="events">
		<th width=105>Date</th>
		<th>Duration</th>
		<th width = 75>Distance</th>
		<th width = 50>Calories Burned</th>
		<th>Pace</th>
		<th>Time of Day</th>
		<th>Min HR</th>
		<th>Avg HR</th>
		<th>Max HR</th>
		</tr>
	<!-- BEGIN SWIM -->
		<tr><td><a href="workouts.php?edit_date={UPDATE}">{DATE}</a></td><td>{DURATION}</td><td>{DISTANCE}</td>
	    		<td>{CALORIES}</td><td>{PACE}</td><td>{TIMEOFDAY}</td>
    	    		<td>{MINHR}</td><td>{AVGHR}</td><td>{MAXHR}</td></tr>
	<!-- END SWIM -->
		</table>
		<table class="events">
	<!-- BEGIN SWIMTOTALS -->
		<tr><td width=101>Totals</td><td>{TOTALDURATION}</td><td width=71>{TOTALDISTANCE}</td><td width=46>{TOTALCALORIES}</td></tr>
	<!-- END SWIMTOTALS -->
		</table>
		<h2>Bike</h2>
		<table class ="events">
		<th width=105>Date</th>
		<th>Duration</th>
		<th>Distance</th>
		<th width=50>Calories Burned</th>
		<th>Avg RPMS</th>
		<th>Pace</th>
		<th>Time of Day</th>
		<th>Min HR</th>
		<th>Avg HR</th>
		<th>Max HR</th>
		</tr>
	<!-- BEGIN BIKE -->
		<tr><td><a href="workouts.php?edit_date={UPDATE}">{DATE}</a></td><td>{DURATION}</td><td>{DISTANCE}</td>
	    		<td>{CALORIES}</td><td>{RPMS}</td><td>{PACE}</td><td>{TIMEOFDAY}</td>
    	    		<td>{MINHR}</td><td>{AVGHR}</td><td>{MAXHR}</td></tr>
	<!-- END BIKE -->
		</table>
		<table class="events">
	<!-- BEGIN BIKETOTALS -->
		<tr><td width=101>Totals</td><td>{TOTALDURATION}</td><td width=39>{TOTALDISTANCE}</td><td width=46>{TOTALCALORIES}</td></tr>
	<!-- END BIKETOTALS -->
		</table>
		<h2>Run</h2>
		<table class ="events">
		<th width=105>Date</th>
		<th>Duration</th>
		<th>Distance</th>
		<th width=50>Calories Burned</th>
		<th>Pace</th>
		<th>Time of Day</th>
		<th>Min HR</th>
		<th>Avg HR</th>
		<th>Max HR</th>
		</tr>
	<!-- BEGIN RUN -->
		<tr><td><a href="workouts.php?edit_date={UPDATE}">{DATE}</a></td><td>{DURATION}</td><td>{DISTANCE}</td>
	    		<td>{CALORIES}</td><td>{PACE}</td><td>{TIMEOFDAY}</td>
    	    		<td>{MINHR}</td><td>{AVGHR}</td><td>{MAXHR}</td></tr>
	<!-- END RUN -->
		</table>
		<table class="events">
	<!-- BEGIN RUNTOTALS -->
		<tr><td width=101>Totals</td><td>{TOTALDURATION}</td><td width=39>{TOTALDISTANCE}</td><td width=46>{TOTALCALORIES}</td></tr>
	<!-- END RUNTOTALS -->
		</table>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>