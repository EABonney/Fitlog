<?php
/* File: 	settings.php
   Desciption:	Implementation file to save user's preferences.
   Author:	Eric A. Bonney
   Date:	September 28, 2009
   Updated:	December 25, 2009 - changed if statement to use mysql_num_rows to test if the
		query returned any rows.
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
$template->loadTemplatefile( "settings.tpl", true, true );

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	//Get the current user's saved settings if any.
	$user_ID = getUserID( $connection );
	$query = "SELECT * FROM user_settings WHERE user_ID = {$user_ID}";
	$result = @ mysql_query( $query, $connection );

	// If we have data fill in the form.
	if( mysql_num_rows( $result ) == 0 )
	{
		$template->setCurrentBlock();
		$template->setVariable( "FIRSTNAME", "" );
		$template->setVariable( "LASTNAME", "" );
		$template->setVariable( "STADDR", "" );
		$template->setVariable( "CITY", "" );
		$template->setVariable( "STATE", "" );
		$template->setVariable( "ZIPCODE", "" );
		$template->setVariable( "NewPass1", "" );
		$template->setVariable( "NewPass2", "" );
		$template->parseCurrentBlock();
	}
	else
	{
		$row = mysql_fetch_array( $result );
		$template->setCurrentBlock();
		$template->setVariable( "FIRSTNAME", $row["FirstName"] );
		$template->setVariable( "LASTNAME", $row["LastName"] );
		$template->setVariable( "STADDR", $row["StreetAddr"] );
		$template->setVariable( "CITY", $row["City"] );
		$template->setVariable( "STATE", $row["State"] );
		$template->setVariable( "ZIPCODE", $row["ZipCode"] );
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

//Show the user's Dashboard page.
$template->show();
?>