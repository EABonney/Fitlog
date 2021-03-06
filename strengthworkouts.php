<?php
/* File: 	strengthworkouts.php
   Desciption:	Implementation file for the entering and editing of strength workouts.
   Author:	Eric A. Bonney
   Date:	December 18, 2009
   Updated:	December 31, 2009 - removed the template stuff relating to the exercise select
		as it is now loaded dynamically with javascript.
*/

require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "Date.php";
require_once "fitlogfunc.php";

$edit_date = $_GET["edit_date"];
$type = $_GET["type"];

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "strengthworkouts.tpl", true, true );

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
	$exerciseDescription;
	$firstRun = 1;
	$userID = getUserID( $connection );
	$yearmoday = explode( "-", $edit_date );

	// See if the users is trying to display a month other than the current month.
	if( $yearmoday != NULL )
	{
		$display_mo = new Date();
		$display_mo->setDayMonthYear( $yearmoday[2], $yearmoday[1], $yearmoday[0] );
	}
	else
		$display_mo = new Date();

	$template->setCurrentBlock( "NAVIGATION" );
	$template->setVariable( "MONTH", $display_mo->format( "%A %B %e, %Y" ) );
	$template->parseCurrentBlock();

	// Load the category select box.
	$template->setCurrentBlock( "CATEGORYSELECT" );
	$query = "SELECT DISTINCT category FROM flexercise_type WHERE user_id={$userID}";
	$result = @ mysql_query( $query, $connection );
	while( $row = mysql_fetch_array( $result ) )
	{
		$template->setVariable( "CATEGORY", $row["category"] );

		// Set the category box selection if any.
		if( setSelectCategoryOption( $category, $row["category"], $template ) )
			$template->setVariable( "SELECTED", 'selected="selected"' );
		
		$template->parseCurrentBlock();
	}

	// Load the templates select box.
	$template->setCurrentBlock( "TEMPLATESELECT" );
	$query = "SELECT DISTINCT description FROM flexercise_templates WHERE user_id={$userID}";
	$result = @ mysql_query( $query, $connection );
	while( $row = mysql_fetch_array( $result ) )
	{
		$template->setVariable( "TEMPLATE", $row["description"] );
		$template->parseCurrentBlock();
	}

	// Get the current days exercise_id from flstrength
	$query = "SELECT * FROM flstrength WHERE user_id={$userID} AND workout_date='" . $edit_date . "'";
	$result = @ mysql_query( $query, $connection );

	if( mysql_num_rows( $result) > 0 )
	{
		$row = mysql_fetch_array( $result );
		// Get the sets information from flexercises
		$query = "SELECT * FROM flexercises where exercises_id={$row["exercises_id"]}";
		$results = @ mysql_query( $query, $connection );
		while( $rowSet = mysql_fetch_array( $results ) )
		{
			// If we have reached the beg of a new exercise, set the desc
			if( intval($rowSet["set_number"]) == 1 && !$firstRun )
			{
				$template->setCurrentBlock( "WORKOUTS" );
				$template->setVariable( "EXECHEADER", $exerciseDescription );
				$template->parseCurrentBlock();
			}

			// Get the category and description of the exercise.
			$query = "SELECT category, exercise FROM flexercise_type WHERE user_id={$userID} AND exercise_type_id={$rowSet["exercise_type_id"]}";
			$exerciseDesc = @ mysql_query( $query, $connection );
			$rowExerciseDesc = mysql_fetch_array( $exerciseDesc );
			$exerciseDescription = $rowExerciseDesc["category"] . ":" . $rowExerciseDesc["exercise"];

			// Populate the template with the current days workouts.
			$template->setCurrentBlock( "SETS" );
			$template->setVariable( "SETNUM", $rowSet["set_number"] );
			$template->setVariable( "REPS", $rowSet["reps"] );
			$template->setVariable( "WEIGHT", $rowSet["weight"] );
			$template->parseCurrentBlock();
			$firstRun=0;
		}

		$template->setCurrentBlock( "WORKOUTS" );
		$template->setVariable( "EXECHEADER", $exerciseDescription );
		$template->parseCurrentBlock();

		$template->setCurrentBlock( "TIMES" );
		$template->setVariable( "STARTTIME", $row["time_of_day"] );
		$template->setVariable( "DURATION", $row["duration"] );
		$template->parseCurrentBlock();
		$template->setCurrentBlock( "NOTES" );
		$template->setVariable( "STRNOTES", $row["notes"] );
		$template->parseCurrentBlock();

		$template->setCurrentBlock( "DATEAREA" );
		$template->setVariable("DATE", $display_mo->format( "%Y-%m-%d" ) );
		$template->setVariable("TYPE", $type );
		$template->setVariable( "STRID", $row["exercises_id"] );
		$template->parseCurrentBlock();
	}
	else
	{
		$template->setCurrentBlock( "DATEAREA" );
		$template->setVariable("DATE", $display_mo->format( "%Y-%m-%d" ) );
		$template->setVariable("TYPE", $type );
		$template->setVariable( "STRID", "0" );
		$template->parseCurrentBlock();
		$template->setCurrentBlock( "TIMES" );
		$template->setVariable( "STARTTIME", "" );
		$template->setVariable( "DURATION", "" );
		$template->parseCurrentBlock();
		$template->setCurrentBlock( "NOTES" );
		$template->setVariable( "STRNOTES", "" );
		$template->parseCurrentBlock();
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

//Show the user the Month View page.
$template->show();

/******************************* Helper Functions ******************************************/
function setSelectCategoryOption( $comp1, $comp2, $template )
{
	if( !strcmp( $comp1, $comp2 ) )
		return true;
	else
		return false;
}
?>