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
		  <li><a href="workoutview.php">Work Outs</a></li>
		  <li><a href="routes.php">Routes</a></li>
		  <li><a href="trainingplans.php">Training Plans</a></li>
		  <li><a href="downloads.php">Downloads</a></li>
		  <li><a href="about.php">About Fitness Log</a></li>
		  <li><a href="logout.php">Log Out</a></li>
		</ul>
	  </div> <!-- end of navigation div -->
	  <!-- BEGIN NAVIGATION -->
	  <div id="bodycontent">
		<h2>Dashboard - {GREETING}{USER}<br />
		    {DATE}<br />
		</h2>
	  <!-- END NAVIGATION -->
	  <div id="widget1">
		<table class="quickcalendar">
		<tr><th colspan=7 align="center">Quick Entry</th></tr>
		<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>
	<!-- BEGIN WEEKLY -->
		<tr><td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYONE}&type=Actual">{DATE1}</a></td>
		<td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYTWO}&type=Actual">{DATE2}</a></td>
		<td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYTHREE}&type=Actual">{DATE3}</a></td>
		<td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYFOUR}&type=Actual">{DATE4}</a></td>
		<td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYFIVE}&type=Actual">{DATE5}</a></td>
		<td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYSIX}&type=Actual">{DATE6}</a></td>
		<td class="{BACKGROUND}"><a href="workouts.php?edit_date={DAYSEVEN}&type=Actual">{DATE7}</a></td></tr>
	<!-- END WEEKLY -->
		</table>
	  </div>
	  <div id="widget2">
		<img src="graphs/wklygraph.jpg" alt="Weekly Volume Graph" />
	  </div>
	  <div id="widget3">
	<!-- BEGIN CURRENTMNTH -->
		<table class="sbr_summaries">
		<tr><th colspan=3 align="center">{MONTHNAME}</th></tr>
		<tr><td></td><td class="highlight">Dur</td><td class="highlight">Dist</td></tr>
		<tr><td>Swim: </td><td align="right">{SWIMDUR}</td><td align="right">{SWIMDIST}</td></tr>
		<tr><td>Bike: </td><td align="right">{BIKEDUR}</td><td align="right">{BIKEDIST}</td></tr>
		<tr><td>Run: </td><td align="right">{RUNDUR}</td><td align="right">{RUNDIST}</td></tr>
		<tr><td>Strength:</td><td align="right">{STRDUR}</td><td></td></tr>
		</table>
	<!-- END CURRENTMNTH -->
	  </div>
	  <div id="widget4">
	<!-- BEGIN CURRENTYR -->
		<table class="sbr_summaries">
		<tr><th colspan=3 align="center">{MONTHNAME}</th></tr>
		<tr><td></td><td class="highlight">Dur</td><td class="highlight">Dist</td></tr>
		<tr><td>Swim: </td><td align="right">{SWIMDUR}</td><td align="right">{SWIMDIST}</td></tr>
		<tr><td>Bike: </td><td align="right">{BIKEDUR}</td><td align="right">{BIKEDIST}</td></tr>
		<tr><td>Run: </td><td align="right">{RUNDUR}</td><td align="right">{RUNDIST}</td></tr>
		<tr><td>Strength:</td><td align="right">{STRDUR}</td><td></td></tr>
		</table>
	<!-- END CURRENTYR -->
	  </div>
	  <div id="widget5">
	<!-- BEGIN PRIORMONTH -->
		<table class="sbr_summaries">
		<tr><th colspan=3 align="center">{MONTHNAME}</th></tr>
		<tr><td></td><td class="highlight">Dur</td><td class="highlight">Dist</td></tr>
		<tr><td>Swim: </td><td align="right">{SWIMDUR}</td><td align="right">{SWIMDIST}</td></tr>
		<tr><td>Bike: </td><td align="right">{BIKEDUR}</td><td align="right">{BIKEDIST}</td></tr>
		<tr><td>Run: </td><td align="right">{RUNDUR}</td><td align="right">{RUNDIST}</td></tr>
		<tr><td>Strength:</td><td align="right">{STRDUR}</td><td></td></tr>
		</table>
	<!-- END PRIORMONTH -->
	  </div>
	  <div id="widget6">
	<!-- BEGIN PRIORYR -->
		<table class="sbr_summaries">
		<tr><th colspan=3 align="center">{MONTHNAME}</th></tr>
		<tr><td></td><td class="highlight">Dur</td><td class="highlight">Dist</td></tr>
		<tr><td>Swim: </td><td align="right">{SWIMDUR}</td><td align="right">{SWIMDIST}</td></tr>
		<tr><td>Bike: </td><td align="right">{BIKEDUR}</td><td align="right">{BIKEDIST}</td></tr>
		<tr><td>Run: </td><td align="right">{RUNDUR}</td><td align="right">{RUNDIST}</td></tr>
		<tr><td>Strength:</td><td align="right">{STRDUR}</td><td></td></tr>
		</table>
	<!-- END PRIORYR -->
	  </div>
	  <div id="widget7">
	<!-- BEGIN TEXTSPOT -->
		<p>{TEXT}</p>
	<!-- END TEXTSPOT -->
	  </div>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>