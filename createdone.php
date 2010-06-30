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
		  <li><a href="about.html">About Fitlog</a></li>
	  </div> <!-- end of navigation div -->
	  <div id="bodycontent">
		<h2>Account Created!</h2>
		<p>Your account has been succussfully created. You will receive an email shortly with instructions on how to confirm your account so that you may begin tracking your workouts!
		</p>
		<p>Please check the following email for your confirmation: 
		<?php echo $_GET["email"]; ?>
		</p>
	  </div> <!-- End of bodycontent div -->
	</body>
</html>