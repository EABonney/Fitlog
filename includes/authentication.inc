<?php
/* File: 	authentication.inc
   Desciption:	Routines used for all authentication, both user and sessions.
   Author:	Eric A. Bonney
   Date:	September 24, 2009
   Updated:	

  Adopted in part from PHP and MySQL, O'Reily Publishing by Hugh E Williams and David Lane
*/

// Function verifies the user is in the database AND that they provided the correct
// password.
function authenticateUser( $connection, $user_name, $passwrd )
{
	// Test the username and password parameteres
	if( !isset( $user_name ) || !isset( $passwrd ) )
		return false;

	// Create a digest of the password collected from the form.
	$psswd_digest = md5( trim( $passwrd ) );

	// Setup the query to find the user.
	$query = "SELECT password FROM users WHERE user_name = '{$user_name}'
		  AND password = '{$psswd_digest}' AND confirmed = true";

	// Run the query
	if( !( $result = @ mysql_query( $query, $connection ) ) )
		showerror();

	// Did we get only one row?
	if( mysql_num_rows( $result ) != 1)
		return false;
	else
		return true;
}

// Connects to a session and checks that the user has been
// authenticated and that the remote IP address matches
// the address used to create the session.
function sessionAuthenticate()
{
	// Check if the user hasn't logged in
	if( !isset( $_SESSION["loginUserName"] ) )
	{
		// No users has been logged in!
		$_SESSION["message"] = "You are not logged into the system!";
	
		header( "Location: logout.php" );
		exit;
	}

	// Check if the request is from a different IP address to what was originally
	// used during authentication. Possible hijack attempt.
	if( !isset( $_SESSION["loginIP"] ) ||
		  ( $_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"] ) )
	{
		// Possible higjack attempt for this session.
		$_SESSION["message"] = "You are not authorized to access this site from
					the address of {$_SERVE["REMOTE_ADDR"]}! This ip
					address has been logged and reported!";

		header( "Location: logout.php" );
		exit;
	}
}
?>