<?php
/* File: 	savesettings.php
   Desciption:	Implementation file to save user's preferences.
   Author:	Eric A. Bonney
   Date:	September 27, 2009
   Updated:	December 25, 2009 - adjusted the UPDATE query to include the userID in the WHERE
		clause.
*/

require_once "includes/db.inc";
require_once "fitlogfunc.php";

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	// Get and clean all the possible settings.
	$first_name = mysqlclean( $_POST, "FirstName", 25, $connection );
	$last_name = mysqlclean( $_POST, "LastName", 25, $connection );
	$street_addr = mysqlclean( $_POST, "StAdd", 50, $connection );
	$city = mysqlclean( $_POST, "City", 25, $connection );
	$state = mysqlclean( $_POST, "State", 2, $connection );
	$zipcode = mysqlclean( $_POST, "ZipCode", 5, $connection );
	$newpass1 = mysqlclean( $_POST, "NewPass1", 16, $connection );
	$newpass2 - mysqlclean( $_POST, "NewPass2", 16, $connection );

	// Get the logged in user's user_id
	$usr_ID = getUserID( $connection );

	// Go ahead and save the personal settings, or update them.
	if( !updateSettings( $usr_ID, $first_name, $last_name, $street_addr, $city, $state, $zipcode, $connection ) )
		showerror();
	else
		header( "Location: settings.php" );	
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

/************************************ Helper Functions for saveSettings.php ***********************************/
function updateSettings( $user_ID, $first_name, $last_name, $street_addr, $city, $state, $zipcode, $connection )
{
	// First we need to see if we have a row setup for this user already in the database.
	// If so then we are going to be updating, if not do an insert.
	$query = "SELECT * FROM user_settings WHERE user_ID = {$user_ID}";
	$result = @mysql_query( $query, $connection );
	if( mysql_num_rows( $result ) != 1 )
	{
		$query = "INSERT INTO user_settings (user_ID, FirstName, LastName, StreetAddr, City, State, ZipCode) VALUE ($user_ID, '$first_name', '$last_name', '$stree_addr', '$city', '$state', '$zipcode')";
	}
	else
	{
		$query = "UPDATE user_settings SET FirstName='{$first_name}', LastName='{$last_name}', StreetAddr='{$street_addr}', City='{$city}', State='{$state}', ZipCode='{$zipcode}' WHERE user_ID={$user_ID}";
	}

	if( !($result = @ mysql_query( $query, $connection ) ) )
		return false;
	else
		return true;
}
?>