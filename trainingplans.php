<?php
/* File: 	trainingplans.php
   Desciption:	Implementation file uploading files.
   Author:	Eric A. Bonney
   Date:	December 2, 2009
   Updated:	
*/
require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "fitlogfunc.php";

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "uploads.tpl", true, true );

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	// See if we have a message set from the session variable, if so display it.
	if ( isset( $_SESSION["upload_status"] ) )
	{
		$template->setVariable( "MESSAGE", $_SESSION["upload_status"] );
		unset( $_SESSION["upload_status"] );
	}
	else
		$template->setVariable( "MESSAGE", "" );
	
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
?>