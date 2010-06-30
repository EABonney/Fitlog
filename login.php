<?php
/* File: 	login.php
   Desciption:	Implementation file to log users into the system.
   Author:	Eric A. Bonney
   Date:	September 24, 2009
   Updated:	
*/

require_once "includes/authentication.inc";
require_once "includes/db.inc";

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

$user_name = mysqlclean($_POST, "username", 25, $connection);
$pass1 = mysqlclean($_POST, "password", 16, $connection);

session_start();

// Authenticate the user.
if( authenticateUser( $connection, $user_name, $pass1 ) )
{
	//Register the username
	$_SESSION["loggedinUserName"] = $user_name;

	//Register the current IP address of the user.
	$_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

	// Send the user to the Dashboard.
	header( "Location: dashboard.php");
	exit;
}
else
{
	//Authentication failed, setup a logout message
	$_SESSION["message"] = "Could not login as '{$user_name}'";

	// Send user to the logout page.
	header( "Location: logout.php" );
	exit;
}
?>