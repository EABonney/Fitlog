<?php
/* File: 	workouts.php
   Desciption:	Implementation file for adding swim, bike, run workouts.
   Author:	Eric A. Bonney
   Date:	October 2, 2009
   Updated:	December 15, 2009 - Fixed a bug where the system was not taking records where no
				    value was entered by the user for HRs, RPMs or Cals. Default
				    value is no set to zero.
*/

require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "Date.php";
require_once "fitlogfunc.php";

$type = $_GET["type"];

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "workouts.tpl", true, true );

session_start();

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();


//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	// Setup the plan type the user is working with.
	if( isset( $type ) )
	{
		switch( $type )
		{
			case "Actual":
				$plan_type = "a";
				$template->setVariable( "PLANTYPE", "a" );
				$template->setVariable( "TYPEOFPLAN", "Actual" );
				break;
			case "Planned":
				$plan_type = "p";
				$template->setVariable( "PLANTYPE", "p" );
				$template->setVariable( "TYPEOFPLAN", "Planned" );
				break;
			default:
				$plan_type = "a";
				$template->setVariable( "PLANTYPE", "a" );
				$template->setVariable( "TYPEOFPLAN", "Actual" );
				break;
		}
	}

	$userID = getUserID( $connection );
	$yearmoday = explode( "-", $_GET["edit_date"] );
	$edited_date = new Date();
	$edited_date->setDayMonthYear( $yearmoday[2], $yearmoday[1], $yearmoday[0] );
	$template->setVariable( "DATE", $edited_date->format( "%A %B %e, %Y" ) );

	// Check to see if we already have anything in the database to display, if so then display it.
	$query = "SELECT * FROM flmain WHERE workout_date='" . $edited_date->format( "%Y-%m-%d" ) . "' AND user_id={$userID} AND plan_type='" . $plan_type . "'";

	// Also check to see if we have any daily blog notes for the given day.
	$query1 = "SELECT * FROM flblog WHERE blog_date='" . $edited_date->format( "%Y-%m-%d" ) . "' AND user_id= {$userID}";

	// Preload zeros in the HR areas.
	$template->setVariable( "SWIMMINHR", "0" );
	$template->setVariable( "SWIMAVGHR", "0" );
	$template->setVariable( "SWIMMAXHR", "0" );
	$template->setVariable( "BIKEMINHR", "0" );
	$template->setVariable( "BIKEAVGHR", "0" );
	$template->setVariable( "BIKEMAXHR", "0" );
	$template->setVariable( "RUNMINHR", "0" );
	$template->setVariable( "RUNAVGHR", "0" );
	$template->setVariable( "RUNMAXHR", "0" );
	$template->setVariable( "SWIMCALS", "0" );
	$template->setVariable( "BIKECALS", "0" );
	$template->setVariable( "RUNCALS", "0" );
	$template->setVariable( "AVGRPMS", "0" );

	// Run the queries to get results.
	$result_main = @ mysql_query( $query, $connection );
	$numRows_main = mysql_num_rows( $result_main );
	$result_blog = @ mysql_query( $query1, $connection );
	$numRows_blog = mysql_num_rows( $result_blog );

	// Display what the users is attempting to do.
	if( $numRows_main )
	{
		// We assume that the users in INSERTING a new row.
		$swim = $bike = $run = FALSE;

		while ( $row = mysql_fetch_array( $result_main ) )
		{
			switch( $row["sbr_type"] )
			{
				case 's':
					$swim = TRUE;
					break;
				case 'b':
					$bike = TRUE;
					break;
				case 'r':
					$run = TRUE;
					break;
			}
		}

		if( $swim )
			$template->setVariable( "UPDATINGSWIM", TRUE );
		else
			$template->setVariable( "UPDATINGSWIM", FALSE );

		if( $bike )
			$template->setVariable( "UPDATINGBIKE", TRUE );
		else
			$template->setVariable( "UPDATINGBIKE", FALSE );

		if( $run )
			$template->setVariable( "UPDATINGRUN", TRUE );
		else
			$template->setVariable( "UPDATINGRUN", FALSE );
	}
	else
	{
		$template->setVariable( "UPDATINGSWIM", FALSE );
		$template->setVariable( "UPDATINGBIKE", FALSE );
		$template->setVariable( "UPDATINGRUN", FALSE );
	}
	if( $numRows_blog)
		$template->setVariable( "UPDATINGBLOGNOTES", TRUE );
	else
		$template->setVariable( "UPDATINGBLOGNOTES", FALSE );

	// Check to see if we already have anything in the database to display, if so then display it.
	$query = "SELECT * FROM flmain WHERE workout_date='" . $edited_date->format( "%Y-%m-%d" ) . "' AND user_id={$userID} AND plan_type='" . $plan_type . "'";
	// Run the queries to get results.
	$result_main = @ mysql_query( $query, $connection );

	// Display the results and set the update session variable.
	displayWorkout( $edited_date, $result_main, $result_blog, $template );

	$template->setVariable( "WORKOUTDATE", $edited_date->format( "%Y-%m-%d" ) );
	$template->parseCurrentBlock();
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

//Show the user's Dashboard page.
$template->show();

/************************************* Helper Functions for workouts.php ****************************************/
function displayWorkout( $edited_date, $result_main, $result_blog, $template )
{
	if( $result_main )
	{
		while( $row = mysql_fetch_array( $result_main ) )
		{
			//Populate the form elements with the row data.
			switch ( $row["sbr_type"] )
			{
				case 's':
					$template->setVariable( "SWIMTIME", $row["time_of_day"] );
					$template->setVariable( "SWIMDUR", $row["duration"] );
					$template->setVariable( "SWIMDIST", $row["distance"] );
					$template->setVariable( "SWIMNOTES", stripslashes( $row["notes"] ) );
					$template->setVariable( "SWIMMINHR", $row["min_hr"] );
					$template->setVariable( "SWIMAVGHR", $row["avg_hr"] );
					$template->setVariable( "SWIMMAXHR", $row["max_hr"] );
					$template->setVariable( "SWIMCALS", $row["cals_burned"] );
					break;
				case 'b':
					$template->setVariable( "BIKETIME", $row["time_of_day"] );
					$template->setVariable( "BIKEDUR", $row["duration"] );
					$template->setVariable( "BIKEDIST", $row["distance"] );
					$template->setVariable( "BIKENOTES", stripslashes( $row["notes"] ) );
					$template->setVariable( "BIKEMINHR", $row["min_hr"] );
					$template->setVariable( "BIKEAVGHR", $row["avg_hr"] );
					$template->setVariable( "BIKEMAXHR", $row["max_hr"] );
					$template->setVariable( "BIKECALS", $row["cals_burned"] );
					$template->setVariable( "AVGRPMS", $row["avg_rpms"] );
					break;
				case 'r':
					$template->setVariable( "RUNTIME", $row["time_of_day"] );
					$template->setVariable( "RUNDUR", $row["duration"] );
					$template->setVariable( "RUNDIST", $row["distance"] );
					$template->setVariable( "RUNNOTES", stripslashes( $row["notes"] ) );
					$template->setVariable( "RUNMINHR", $row["min_hr"] );
					$template->setVariable( "RUNAVGHR", $row["avg_hr"] );
					$template->setVariable( "RUNMAXHR", $row["max_hr"] );
					$template->setVariable( "RUNCALS", $row["cals_burned"] );
					break;				
			}
		}
	}

	if( $result_blog )
	{
		$row = mysql_fetch_array( $result_blog );
		$template->setVariable( "DAILYNOTES", $row["notes"] );
	}
}
?>