<?php
/* File: 	ftnav.php
   Desciption:	Implementation file for the navigation of months in the various view.
   Author:	Eric A. Bonney
   Date:	February 18, 2009
   Updated:	October 1, 2009
		Modified to work in the stand alone application. Removed WordPress items.
		December 16, 2009 - added code for the navigation on the strength workous.
*/

$month = $_GET["month"];
$source = $_GET["source"];
$type = $_GET["type"];

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{

	// Set the cookie to where the user wants to actually go.
	$_SESSION["nav_month"] = $month;
	switch( $source )
	{
		case "summary":
			// re-direct back to the workouts main page.
			header( "Location: workoutview.php?display=" . $type );
			break;
		case "strength":
			// re-direct back to the strength main page.
			header( "Location: strengthview.php?display=" . $type );
			break;
/*		case "history":
			$page = get_page_by_title( "Fitness Log" );
			$url = get_bloginfo(wpurl) . "/?page_id=" . $page->ID;
			break;*/
	}
}
else
{
	//Seems the user has attempted to navigate directly to the dashboard without
	//logging in. Send them to the logout page with an error message.
	$_SESSION["headerMessage"] = "Error!!";
	$_SESSION["message"] = "You must first log into the system before you can view the page.";

	// Send user to the logout page.
	header( "Location: logout.php" );
	exit;
}
?>