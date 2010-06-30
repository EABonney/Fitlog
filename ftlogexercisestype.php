<?php
/* File: 	fitlogexercisetype.php
   Desciption:	Implementation file for the actual submitting and inserting of new exercise types.
   Author:	Eric A. Bonney
   Date:	February 26, 2009
   Updated:	December 17, 2009 - reworked to make it work as a stand alone application.
*/
require_once "includes/db.inc";
require_once "fitlogfunc.php";

$category = $_POST["exercise_category"];
$description = $_POST["exercise_desc"];
$updating = $_POST["updating"];
$exercise_type_id = $_POST["exercise_type_id"];

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

	if( $updating )
		$query = "UPDATE flexercise_type SET category='" . $category . "', exercise='" . $description . "' WHERE user_id={$userID} AND exercise_type_id={$exercise_type_id}";
	else
		$query = "INSERT INTO flexercise_type (exercise_type_id, category, exercise, user_id) VALUES ( NULL, '$category', '$description', {$userID} )";

	$result = @ mysql_query( $query, $connection );

	if( $result )
	{
		// Send the user back to the main Exercise page again.
		header( "Location: exercises.php" );
	}
	else
		header( "Location: exercises.php" );

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