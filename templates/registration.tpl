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
		  <li><a href="index.html">Home</a></li>
		  <li><a href="about.php">About Fitness Log</a></li>
	  </div> <!-- end of navigation div -->
	  <div id="bodycontent">
		<p>Registration is simple. Select a valid username, supply a valid email address and choose a password. You will be sent a confirmation email shortly. Follow the instructions in the confirmation email and you will be ready to start tracking!
		</p>
		<p>
		<form action="createaccount.php" method="post">
		<table>
		<tr><td><label for="username">User Name:</label></td>
		<td><input type="text" name="username" id="username" TABINDEX=1/></td>
		<td>{ERROR_USERNAME}</td></tr>
		<tr><td><label for="email">Email Address:</label></td>
		<td><input type="text" name="email" id="email" TABINDEX=2/></td>
		<td>{ERROR_EMAIL}</td></tr>
		<tr><td><label for="password">Password:</label></td>
		<td><input type="password" name="password" id="password" TABINDEX=3/></td>
		<td>{ERROR_PASSWORD}</td></tr>
		<tr><td><label for="confirmpass">Confirm Password:</label></td>
		<td><input type="password" name="confirmpass" id="confirmpass" TABINDEX=4/></td>
		<td>{ERROR_CONFIRM}</td></tr>
		</table>
		<p class="submit"><input type="submit" value="Create Account">
		<input type="reset" value="Clear" /></p>
		</form>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>