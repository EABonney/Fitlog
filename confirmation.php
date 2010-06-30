<?php
/* File: 	confirmation.php
   Desciption:	Implementation file for confirming registered users.
   Author:	Eric A. Bonney
   Date:	September 24, 2009
   Updated:	
*/
require_once "includes/db.inc";

session_start();

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

$user_name = mysqlclean($_GET, "username", 25, $connection);
$email = mysqlclean($_GET, "email", 50, $connection);

// See if this username and email address is actually in the database and
// not already confirmed.
$query = "SELECT * FROM users WHERE user_name = '" . $user_name . "' AND email = '" . $email . "' AND
	  confirmed = false";

if( !( $result = @ mysql_query ( $query, $connection ) ) )
	showerror();

// Did we get only one row?
if( mysql_num_rows( $result ) != 1)
{
	// Either the account has already been confirmed or something doesn't match.
	$_SESSION["headerMessage"] = "Confirmation Error!";
	$_SESSION["message"] = "Either this account has already been confirmed or the user name and
				email that you provided did not match.";
	header( "Location: logout.php" );
}
else
{
	// User is infact the correct one, update the account and redirect to login page.
	$query = "UPDATE users SET confirmed = true WHERE user_name = '{$user_name}'";

	if( !( $result = @ mysql_query( $query, $connection ) ) )
		showerror();

	$_SESSION["headerMessage"] = "Confirmation Success!";
	$_SESSION["message"] = "Thank you '{$user_name}', your account has been activated and you may now
				log in.";

	header( "Location: logout.php" );
}
?>