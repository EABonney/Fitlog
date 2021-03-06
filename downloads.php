<?php
/* File: 	dashboard.php
   Desciption:	Implementation file for the users' dashboard page.
   Author:	Eric A. Bonney
   Date:	September 25, 2009
   Updated:	
*/
require_once "HTML/Template/IT.php";

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "downloads.tpl", true, true );

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	// List files in the downloads directory.
	$template->setCurrentBlock( "DOWNLOADS" );
	$template->setVariable( "FILENAME", "upload-template.csv" );
	$template->setVariable( "DESCRIPTION", "Use this template to upload any workout data to your logs." );
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