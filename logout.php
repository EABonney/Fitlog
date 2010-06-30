<?php
/* File: 	login.php
   Desciption:	Implementation file to log users into the system.
   Author:	Eric A. Bonney
   Date:	September 24, 2009
   Updated:	
*/

require_once "HTML/Template/IT.php";

//Create the template object and load the correct template.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "logout.tpl", true, true );
session_start();

$message = "";

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	$template->setVariable( "HEADERMSG", "You have been logged out!" );
	$message .= "Thanks {$_SESSION["loggedinUserName"]} for using Fitlog Training Center.";
}

//Some other script may have set up a logout message already.
if( isset( $_SESSION["message"] ) )
{
	$template->setVariable( "HEADERMSG", $_SESSION["headerMessage"] );
	$message .= $_SESSION["message"];
	unset( $_SESSION["message"] );
}

//Destroy the session.
session_destroy();

//Display the logout page
$template->setVariable( "MESSAGE", $message );
$template->parseCurrentBlock();
$template->show();
?>