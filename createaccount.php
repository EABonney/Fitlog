<?php
/* File: 	createaccount.php
   Desciption:	Implementation file for registering accounts.
   Author:	Eric A. Bonney
   Date:	September 22, 2009
   Updated:	
*/
require_once "includes/db.inc";
require_once "HTML/Template/ITX.php";
require_once "Mail.php";
require_once "fitlogfunc.php";

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

$user_name = mysqlclean($_POST, "username", 25, $connection);
$email = mysqlclean($_POST, "email", 50, $connection);
$pass1 = mysqlclean($_POST, "password", 16, $connection);
$pass2 = mysqlclean($_POST, "confirmpass", 16, $connection);

// First let's see if the username is already in the database, if so error out
// and tell the user.
$query = "SELECT user_name FROM users WHERE user_name='" . $user_name . "'";

if( !( $result = @ mysql_query ( $query, $connection ) ) )
	showerror();
else
{
	// check to see if we have any results.
	if( mysql_num_rows( $result ) )
		die("Can't use the selected user name, it is already registered: " . $user_name );
}

// See if the email address already is registered to a user.
$query = "SELECT email FROM users WHERE email='" . $email . "'";
	
if( !( $result = @ mysql_query ( $query, $connection ) ) )
	showerror();
else
{
	// check to see if we have any results.
	if( mysql_num_rows( $result ) )
		die("There is already an account registered to this email: " . $email );
}

// Verify somehow that we actually have an email address, or at least the correct format.
if( !validate_email( $email ) )
	die( "We have an error in the email address!" );

// Check to see if the two passwords are exactly the same.
if( $pass1 != $pass2 )
	die( "Passwords do not match!" );		// We have an error again!

// Create the digest of the password
$digest = md5( trim( $pass1 ) );

// If we are here then this user must be ok to add to the table
$query = "INSERT INTO users (user_id, user_name, email, password, confirmed) VALUES ( 'NULL', '$user_name', '$email', '$digest', 'false' )";

if( !(@ mysql_query ( $query, $connection ) ) )
	showerror();		// We have an error and will deal with it later.
else
{
	send_confirmation( $user_name, $email );
	header( "Location: createdone.php?email=" . $email );
}

function send_confirmation( $user_name, $email )
{
	// Create a new template, and specify that the template files are
	// in the same directory as the as the php files.
	$template = new HTML_Template_IT( "./templates" );
	
	// Load the email template file
	$template->loadTemplatefile( "confirmemail.tpl", true, true );

	$template->setVariable( "USERNAME", $user_name );
	$template->setVariable( "EMAIL", $email );

	$to = $email;

	// Setup the headers.
	$headers["From"] = "noreply@vanhlebarsoftware.com";
	$headers["Subject"] = "Fitness Log Confirmation Email";
	$headers["X-Sender"] = "noreply@vanhlebarsoftware.com";
	$headers["X-Mailer"] = "PHP";
	$headers["Return-Path"] = "noreply@vanhlebarsoftware.com";
	$headers["To"] = $email;

	$body = $template->get();	

	$host = "mail.vanhlebarsoftware.com";
	$mail_user = "noreply@vanhlebarsoftware.com";
	$password = "Tanis*1973";

	$smtp = Mail::factory('smtp',
	  array ('host' => $host,
	    'auth' => true,
	    'username' => $mail_user,
	    'password' => $password));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail))
		echo("<p>" . $mail->getMessage() . "</p>");
}
?>