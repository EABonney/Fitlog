<?php
/* File: 	routes.php
   Desciption:	Implementation file for passing user's address information on to the mapping javascript.
   Author:	Eric A. Bonney
   Date:	December 2, 2009
   Updated:	January 4, 2010 - updated so that the file can direct the user to either the main
				  view or to the actual mapping view.
*/

require_once "includes/db.inc";
require_once "fitlogfunc.php";
require_once "HTML/Template/IT.php";

$view = $_GET["view"];

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	switch( $view )
	{
		case 's':
			$template->loadTemplatefile( "routesmainview.tpl", true, true );
			loadMainView( $template, $connection );
			break;
		case 'm':
			$template->loadTemplatefile( "routes.tpl", true, true );
			$template->setVariable( "MONTH", "");
			$template->parseCurrentBlock();
			break;
		default:
			$template->loadTemplatefile( "routesmainview.tpl", true, true );
			loadMainView( $template, $connection );
			break;
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

//Show the user the Routs Main View page.
$template->show();
/********************************** Helper functions *****************************************/
function loadMainView( $template, $connection )
{
	// Get the logged in user's user_id
	$usr_ID = getUserID( $connection );

	// Fill in the user's routes.
	$template->setCurrentBlock( "COURSELISTING" );
	$template->setVariable( "DESC", "" );
	$template->parseCurrentBlock();
}
?>