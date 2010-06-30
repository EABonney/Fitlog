<?php
/* File: 	exercises.php
   Desciption:	Implementation file for creating, deleting and listing all exercises.
   Author:	Eric A. Bonney
   Date:	December 16, 2009
   Updated:	
*/

require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "fitlogfunc.php";

$updating = $_GET[ "updating" ];
$exercise_type_id = $_GET[ "Id" ];

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "exercises.tpl", true, true );

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

	// See if the user is editing an exercise type.
	if( $updating )
	{
		$query = "SELECT * FROM flexercise_type WHERE user_id={$userID} AND exercise_type_id={$exercise_type_id}";
		$result = @ mysql_query( $query, $connection );
		$row = mysql_fetch_array( $result );

		// Populate the fields with the selected exercise.
		$template->setCurrentBlock( "INPUT" );
		$template->setVariable( "EXERCISECAT", $row["category"] );
		$template->setVariable( "EXERCISEDESC", $row["exercise"] );
		$template->setVariable( "EXERCISETYPEID", $exercise_type_id );
		$template->setVariable( "UPDATING", TRUE );
		$template->parseCurrentBlock();
	}
	else
	{
		$template->setCurrentBlock( "INPUT" );
		$template->setVariable( "EXERCISECAT", "" );
		$template->setVariable( "EXERCISEDESC", "" );
		$template->setVariable( "UPDATING", FALSE );
		$template->parseCurrentBlock();
	}

	$query = "SELECT * FROM flexercise_type WHERE user_id={$userID} ORDER BY category ASC";

	$result = @ mysql_query( $query, $connection );

	$template->setCurrentBlock( "EXERCISELIST" );

	while( $row = mysql_fetch_array( $result ) )
	{
		$template->setVariable( "CATEGORY", $row["category"] );
		$template->setVariable( "DESCRIPTION", $row["exercise"] );
		$template->setVariable( "EXERCISEID", $row["exercise_type_id"] );
		$template->setVariable( "ID", $row["exercise_type_id"] );
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

/******************************* Helper Functions ***************************************/
?>