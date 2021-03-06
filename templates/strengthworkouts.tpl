<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
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
		<!-- BEGIN NAVIGATION -->
		<h2>{MONTH}</h2>
		<!-- END NAVIGATION -->
		<div id="strengthworkouts">
			<form id="exercises">
			<!-- BEGIN DATEAREA -->
			<input type="hidden" id="date" name="date" value="{DATE}" />
			<input type="hidden" id="type" name="type" value="{TYPE}" />
			<input type="hidden" id="strid" name="strid" value="{STRID}" />
			<!-- END DATEAREA -->
			<label for="category">Categoy:</label>
			<select id="category" name="category" onChange="loadExercises()">
				<option value="none" selected="selected"></option>
			<!-- BEGIN CATEGORYSELECT -->
				<option value="{CATEGORY}" {SELECTED}>{CATEGORY}</option>
			<!-- END CATEGORYSELECT -->
			</select>
			<label for="exercises">Exercises:</label>
			<select id="exercise" name="exercise">
			</select>
			<input type="text" id="sets" value="3" class="str_input" />
			<input type="button" id="Add" value="Add" name="Add" disabled="true" onclick="addWorkout(this.form)" />
			<input type="button" id="savebtn" onclick="saveDay(this)" value="Save" />
			<!-- BEGIN NOTES -->
			<div id="strnote" class="str_notes">
				<label for="strnotes">Notes:</label>
				<textarea id="strnotes" name="strnotes" cols="35" rows="10" >{STRNOTES}</textarea>
			</div>
			<!-- END NOTES -->
			<!-- BEGIN TIMES -->
			<div id="strtimes">
				<label for="strtime">Time:<span class="small">(hh:mm:ss)</span></label>
				<input type="text" id="start_time" name="start_time" value="{STARTTIME}"/>
				<label for="strduration">Duration:<span class="small">(hh:mm:ss)</span></label>
				<input type="text" id="duration" name="duration" value="{DURATION}"/>
			</div>
			<!-- END TIMES -->
			<div id="strengthtables">
			<!-- BEGIN WORKOUTS -->
				<table id="workouts" class="str_workouts float">
				<tr><td class="exerciseheader" colspan="3">{EXECHEADER}</td>
				<td colspan="2"><input type="button" name="delete" value="Del" onclick="deleteExercise(this)" /></td>
				</tr>
				<tbody>
				<tr><td class="set"></td>
				<td class="reps">Reps</td>
				<td class="wght">Wght</td>
				<td>Lbs</td>
				</tr>
				<!-- BEGIN SETS -->
				<tr>
				<td>{SETNUM}</td>
				<td><input type="text" class="str_reps" name="reps" value="{REPS}" /></td>
				<td><input type="text" class="str_weight" name="weight" value="{WEIGHT}" /></td>
				<td><input type="button" name="insertSet" value="I" onclick="insertSet(this)" /></td>
				<td><input type="button" name="deleteSet" value="D" onclick="deleteSet(this)" /></td>
				</tr>
				<!-- END SETS -->
				</tbody>
				</table>
			<!-- END WORKOUTS -->
			</div>
			</form>
		</div>
		<div id="strengthtemplates" class="strengthtemplates">
			<label for="strtemplates">Save today as a template</label>
			<input type="text" id="template" name="template" />
			<input type="button" id="saveTemplate" value="Save Template" name="saveTemplate" onclick="saveTemplate(this)" />
		</div>
		<div id="selecttemplate" class="selecttemplate">
			<select id="str_templates" name="str_templates">
			<!-- BEGIN TEMPLATESELECT -->
				<option value="{TEMPLATE}" {SELECTED}>{TEMPLATE}</option>
			<!-- END TEMPLATESELECT -->
			</select>
			<input type="button" id="tmplloadbtn" value="Load" onclick="loadTemplate(this)" />
			<input type="button" id="tmpldelbtn" value="Delete" onclick="deleteTemplate()" />
		</div>
	</div> <!-- End of bodycontent div -->
	</body>
</html>