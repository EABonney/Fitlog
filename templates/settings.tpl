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
	  <div id="bodycontent">
		<h2>Settings</h2>
		<form action="http://www.vanhlebarsoftware.com/fitlog/savesettings.php" method="post">
			<fieldset>
			<legend>Personal Information</legend>
			<table>
			<tr><td><label for="FirstName">First Name</label></td>
			<td><input type="text" id="FirstName" name="FirstName" value="{FIRSTNAME}"/></td></tr>
			<tr><td><label for="LastName">Last Name</label></td>
			<td><input type="text" id="LastName" name="LastName" value="{LASTNAME}"/></td></tr>
			<tr><td><label for="StAdd">Street Address</label></td>
			<td><input type="text" id="StAdd" name="StAdd" value="{STADDR}"/></td></tr>
			<tr><td><label for="City">City</label></td>
			<td><input type="text" id="City" name="City" value="{CITY}"/></td></tr>
			<tr><td><label for="State">State</label></td>
			<td><input type="text" id="State" name="State" value="{STATE}"/></td></tr>
			<tr><td><label for="ZipCode">Zip Code</label></td>
			<td><input type="text" id="ZipCode" name="ZipCode" value="{ZIPCODE}"/></td></tr>
			</table>
			<p class="submit"><input type="submit" value="Save" /></p>
			</fieldset>
		</form>
		<form action="" method="post">
			<fieldset>
			<legend>Change Password</legend>
			<table>
			<tr><td><label for="NewPass1">New Password</label></td>
			<td><input type="password" id="NewPass1" name="NewPass1" /></td></tr>
			<tr><td><label for="NewPass2">Confirm Password</label></td>
			<td><input type="password" id="NewPass2" name="NewPass2" /></td></tr>
			</table>
			<p class="submit"><input type="submit" value="Save" /></p>
			</fieldset>
		</form>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>