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
		  <li><a href="about.php">About Fitlog</a></li>
		  <li><a href="logout.php">Log Out</a></li>
		</ul>
	  </div> <!-- end of navigation div -->
	  <div id="bodycontent">
		<h2>{DATE}
		<p>{TYPEOFPLAN}</p></h2>
	 <form action="fitlogsubmit.php" method="post">
	 <input type="hidden" name="plantype" id="plantype" value="{PLANTYPE}"/>
	 <table>
	 <tr><td></td><td align=center><h2>Swimming</h2></td><td></td><td align=center><h2>Biking</h2></td><td></td><td align=center><h2>Running</h2></td></tr>
	 <td><input type="hidden" name="s_workoutdate" id="s_workoutdate" value="{WORKOUTDATE}" TABINDEX=1/></td><td></td>
	 <td><input type="hidden" name="b_workoutdate" id="b_workoutdate"  value="{WORKOUTDATE}" TABINDEX=10/></td><td></td>
	 <td><input type="hidden" name="r_workoutdate" id="r_workoutdate"  value="{WORKOUTDATE}" TABINDEX=20/></td></tr>
	 <tr><td><label for="workouttime">Workout time <span class="small">(hh:mm:ss)</span>:</label></td>
	 <td><input type="text" name="s_workouttime" id="s_workouttime" value="{SWIMTIME}" TABINDEX=2/></td><td></td>
	 <td><input type="text" name="b_workouttime" id="b_workouttime" value="{BIKETIME}" TABINDEX=11/></td><td></td>
	 <td><input type="text" name="r_workouttime" id="r_workouttime" value="{RUNTIME}" TABINDEX=21/></td></tr>
	 <tr><td><label for="duration">Total Time <span class="small">(hh:mm:ss)</span>:</label></td>
	 <td><input type="text" name="s_duration" id="s_duration" value="{SWIMDUR}" TABINDEX=3/></td><td></td>
	 <td><input type="text" name="b_duration" id="b_duration" value="{BIKEDUR}" TABINDEX=12/></td><td></td>
	 <td><input type="text" name="r_duration" id="r_duration" value="{RUNDUR}" TABINDEX=22/></td><td></td></tr>
	 <tr><td><label for="distance">Distance:</label></td>
	 <td><input type="text" name="s_distance" id="s_distance" value="{SWIMDIST}" TABINDEX=4/></td><td></td>
	 <td><input type="text" name="b_distance" id="b_distance" value="{BIKEDIST}" TABINDEX=13/></td><td></td>
	 <td><input type="text" name="r_distance" id="r_distance" value="{RUNDIST}" TABINDEX=23/></td><td></td></tr>
	 <tr><td><label for="notes">Notes: (max 500)</label></td>
	 <td><textarea id="s_notes" name="s_notes" cols="29" rows="10" TABINDEX=5>{SWIMNOTES}</textarea></td><td></td>
	 <td><textarea id="b_notes" name="b_notes" cols="29" rows="10" TABINDEX=14>{BIKENOTES}</textarea></td><td></td>
	 <td><textarea id="r_notes" name="r_notes" cols="29" rows="10" TABINDEX=25>{RUNNOTES}</textarea></td></tr>
	 <tr><td><label for="avg_rpms">Avg RPMS:</label></td>
	 <td></td><td></td><td><input type="text" name="avg_rpms" id="avg_rpms" value="{AVGRPMS}" TABINDEX=15/></td></tr>
 	 <tr><td><label for="min_hr">Min Hr:</label></td>
	 <td><input type="text" name="s_min_hr" id="s_min_hr" value="{SWIMMINHR}" TABINDEX=6/></td><td></td>
	 <td><input type="text" name="b_min_hr" id="b_min_hr" value="{BIKEMINHR}" TABINDEX=16/></td><td></td>
	 <td><input type="text" name="r_min_hr" id="r_min_hr" value="{RUNMINHR}" TABINDEX=26/></td><td></td></tr>
	 <tr><td><label for="avg_hr">Avg Hr:</label></td>
	 <td><input type="text" name="s_avg_hr" id="s_avg_hr" value="{SWIMAVGHR}" TABINDEX=7/></td><td></td>
	 <td><input type="text" name="b_avg_hr" id="b_avg_hr" value="{BIKEAVGHR}" TABINDEX=17/></td><td></td>
	 <td><input type="text" name="r_avg_hr" id="r_avg_hr" value="{RUNAVGHR}" TABINDEX=27/></td><td></td></tr>
	 <tr><td><label for="max_hr">Max Hr:</label></td>
	 <td><input type="text" name="s_max_hr" id="s_max_hr" value="{SWIMMAXHR}" TABINDEX=8/></td><td></td>
	 <td><input type="text" name="b_max_hr" id="b_max_hr" value="{BIKEMAXHR}" TABINDEX=18/></td><td></td>
	 <td><input type="text" name="r_max_hr" id="r_max_hr" value="{RUNMAXHR}" TABINDEX=28/></td><td></td></tr>
	 <tr><td><label for="calsburned">Calories Burned:</label></td>
	 <td><input type="text" name="s_calsburned" id="s_calsburned" value="{SWIMCALS}" TABINDEX=9/></td><td></td>
	 <td><input type="text" name="b_calsburned" id="b_calsburned" value="{BIKECALS}" TABINDEX=19/></td><td></td>
	 <td><input type="text" name="r_calsburned" id="r_calsburned" value="{RUNCALS}" TABINDEX=29/></td><td></td>
	 <tr><td><label for="blog_notes">Overall Daily Notes:</label></td>
	 <td colspan=6><textarea id="blog_notes" name="blog_notes" cols="97" rows="10" TABINDEX=30>{DAILYNOTES}</textarea></td></tr>
	 </table>
	 <input type="hidden" name="updatingswim" id="updatingswim" value="{UPDATINGSWIM}"/>
	 <input type="hidden" name="updatingbike" id="updatingbike" value="{UPDATINGBIKE}"/>
	 <input type="hidden" name="updatingrun" id="updatingrun" value="{UPDATINGRUN}"/>
	 <input type="hidden" name="updatingblognotes" id="updatingblognotes" value="{UPDATINGBLOGNOTES}"/>
	 <p><input type="submit" value="Save Workouts" />
	 <input type="reset" value="Reset" /></p>
	 </form> 
	 </div> <!-- End of bodycontent div -->
	</body>
</html>