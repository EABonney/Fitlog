<?php
/* File: 	fitlogdelete.php
   Desciption:	Implementation file for the actual deleting of workouts.
   Author:	Eric A. Bonney
   Date:	February 16, 2009
   Updated:	February 27, 2009
		December 2009 - Removed all Wordpress items and reworked to work as a stand alone script.
*/

require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "Date.php";
require_once 'Calendar/Month/Weekdays.php';
require_once 'Calendar/Decorator.php';
require_once "fitlogfunc.php";

$delete_date = $_GET["del_date"];
$activity = $_GET["activity"];
$source = $_GET["source"];
$exercise_type_id = $_GET["exercise_type_id"];
$exercise_id = $_GET["strid"];
$type = $_GET["type"];

switch( $type )
{
	case "Actual":
		$planType = 'a';
		break;
	case "Planned":
		$planType = 'p';
		break;
	default:
		$planType = 'a';
		break;
}

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
	$userID = getUserID( $connection );

	// See what the user is attempting to delete.
	switch ( $source )
	{
		case "workouts":
			// Build and run the query per the users request.
			switch ( $activity )
			{
				case 'a':
					$query = "DELETE FROM flmain WHERE user_id={$userID} AND workout_date='" . $delete_date . "' AND plan_type='" . $planType . "'";
					$query1 = "DELETE FROM flblog WHERE user_id={$userID} AND blog_date='" . $delete_date . "'";
					$results = @ mysql_query( $query, $connection );
					$results = @ mysql_query( $query1, $connection );
					break;
				case 's':
				case 'b':
				case 'r':
					$query = "DELETE FROM flmain WHERE user_id={$userID} AND sbr_type='" . $activity . "' AND workout_date='" . $delete_date . "' AND plan_type='" . $planType . "'";
					$results = @ mysql_query( $query, $connection );
					break;
			}

			// Send user to the workoutview page.
			header( "Location: workoutview.php" );
			break;
		case "exercise_type":
			$query = "DELETE FROM flexercise_type WHERE user_id={$userID} AND exercise_type_id={$exercise_type_id}";
			$results = @ mysql_query( $query, $connection );
			
			// Send user to the exercise page.
			header( "Location: exercises.php" );
			break;
		case "strength":
			switch( $activity )
			{
				case 'a':
					/* First get all the strength exercise ids for this date
					   for this user. */
					$query = "SELECT exercises_id FROM flstrength WHERE user_id={$userID} AND plan_type='" . $planType . "' AND workout_date='" . $delete_date . "'";
					$resultIDs = @ mysql_query( $query, $connection );
					while( $row = mysql_fetch_array( $resultIDs ) )
					{
						/* Delete all the records in flexercises for this ID for this user. */
						$query = "DELETE FROM flexercises WHERE exercises_id={$row["exercises_id"]}";
						$result = @ mysql_query( $query, $connection );

						/* Now delete the record in flstrength for this ID for this user */
						$query = "DELETE FROM flstrength WHERE user_id={$userID} AND exercises_id={$row["exercises_id"]}";
						@ mysql_query( $query, $connection );
					}
					break;
				case 's':
					/* Delete all the records in flexercises for this ID for this user. */
					$query = "DELETE FROM flexercises WHERE exercises_id={$exercise_id}";
					$result = @ mysql_query( $query, $connection );

					/* Now delete the record in flstrength for this ID for this user */
					$query = "DELETE FROM flstrength WHERE user_id={$userID} AND exercises_id={$exercise_id}";
					@ mysql_query( $query, $connection );
					break;
			}
			// Send the user to the strength page.
			header( "Location: strengthview.php" );
			break;
	}
	exit;
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