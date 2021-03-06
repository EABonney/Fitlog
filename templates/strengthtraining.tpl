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
	<!-- BEGIN NAVIGATION -->
	<h2>{MONTH}</h2>
	<h2>
	    <form action="no action">
	    <a href="ftnav.php?month={PREVIOUS}&source=strength&type={TYPE}"><<</a> 
	    <a href="ftnav.php?source=strength&type={TYPE}">Current</a> 
	    <a href="ftnav.php?month={NEXT}&strength=summary&type={TYPE}">>></a>
	    <select name="Type" onChange="reloadStrengthView(this.form)">
		<option value="Actual" {SELECTEDA}>Actual</option>
		<option value="Planned" {SELECTEDP}>Planned</option>
	    </select>
	    </form>
	</h2>
	<!-- END NAVIGATION -->
	 <table class="workoutview">
	 <tr>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Monday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Tuesday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Wednesday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Thursday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Friday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Saturday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Sunday</th>
	 <th COLSPAN=4 WIDTH=257 HEIGHT=20 ALIGN=CENTER VALIGN=MIDDLE>Totals</th>
	 </tr>
	 <!-- BEGIN WEEK -->
	 <tr>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE1}&type={TYPE}">{ONE}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE1}&activity=a&source=strength&type={TYPE}">{DELALL1}</a></td>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE2}&type={TYPE}">{TWO}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE2}&activity=a&source=strength&type={TYPE}">{DELALL2}</a></td>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE3}&type={TYPE}">{THREE}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE3}&activity=a&source=strength&type={TYPE}">{DELALL3}</a></td>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE4}&type={TYPE}">{FOUR}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE4}&activity=a&source=strength&type={TYPE}">{DELALL4}</a></td>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE5}&type={TYPE}">{FIVE}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE5}&activity=a&source=strength&type={TYPE}">{DELALL5}</a></td>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE6}&type={TYPE}">{SIX}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE6}&activity=a&source=strength&type={TYPE}">{DELALL6}</a></td>
	 <td class="datecell"><a href="strengthworkouts.php?edit_date={UPDATE7}&type={TYPE}">{SEVEN}</a></td><td class="uppercell"></td><td class="uppercell"></td><td class="upperrightcell"><a href="fitlogdelete.php?del_date={DELALLDATE7}&activity=a&source=strength&type={TYPE}">{DELALL7}</a></td>
	 </tr>
	 <tr><td class="leftcell">{STR1}</td><td>{STRDIST1}</td><td>{STRDUR1}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE1}&activity=s&source=strength&type={TYPE}&strid={STRID1}">{STRDEL1}</a></td>
	     <td class="leftcell">{STR2}</td><td>{STRDIST2}</td><td>{STRDUR2}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE2}&activity=s&source=strength&type={TYPE}&strid={STRID2}">{STRDEL2}</a></td>
	     <td class="leftcell">{STR3}</td><td>{STRDIST3}</td><td>{STRDUR3}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE3}&activity=s&source=strength&type={TYPE}&strid={STRID3}">{STRDEL3}</a></td>
	     <td class="leftcell">{STR4}</td><td>{STRDIST4}</td><td>{STRDUR4}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE4}&activity=s&source=strength&type={TYPE}&strid={STRID4}">{STRDEL4}</a></td>
	     <td class="leftcell">{STR5}</td><td>{STRDIST5}</td><td>{STRDUR5}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE5}&activity=s&source=strength&type={TYPE}&strid={STRID5}">{STRDEL5}</a></td>
	     <td class="leftcell">{STR6}</td><td>{STRDIST6}</td><td>{STRDUR6}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE6}&activity=s&source=strength&type={TYPE}&strid={STRID6}">{STRDEL6}</a></td>
	     <td class="leftcell">{STR7}</td><td>{STRDIST7}</td><td>{STRDUR7}</td><td class="rightcell"><a href="fitlogdelete.php?del_date={STRDELDATE7}&activity=s&source=strength&type={TYPE}&strid={STRID7}">{STRDEL7}</a></td></tr>
	 <tr><td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td>
	     <td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td>
	     <td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td>
	     <td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td>
	     <td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td>
	     <td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td>
	     <td class="lowerleftcell"></td><td class="lowercell"></td><td class="lowercell"></td><td class="lowerrightcell"></td></tr>
	 <!-- END WEEK -->
	 </table>
	 </div> <!-- End of bodycontent div -->
	</body>
</html>