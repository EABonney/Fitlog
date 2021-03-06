<?php
/* File: 	about.php
   Desciption:	Implementation file for the about page.
   Author:	Eric A. Bonney
   Date:	September 25, 2009
   Updated:	
*/

require_once "HTML/Template/IT.php";

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "about.tpl", true, true );

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	//Populate the navigation menu correcltly.
	$template->setCurrentBlock( "NAVIGATIONMENU" );
	$template->setVariable( "PAGE", "dashboard.php" );
	$template->setVariable( "TITLE", "Home" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "settings.php" );
	$template->setVariable( "TITLE", "Settings" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "reports.php" );
	$template->setVariable( "TITLE", "Reports" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "workoutview.php" );
	$template->setVariable( "TITLE", "Work Outs" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "routes.php" );
	$template->setVariable( "TITLE", "Routes" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "trainingplans.php" );
	$template->setVariable( "TITLE", "Training Plans" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "downloads.php" );
	$template->setVariable( "TITLE", "Downloads" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "about.php" );
	$template->setVariable( "TITLE", "About Fitness Log" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "logout.php" );
	$template->setVariable( "TITLE", "Log Out" );
	$template->parseCurrentBlock();
}
else
{
	//User is not logged in yet, so setup the proper navigation menu.
	$template->setCurrentBlock( "NAVIGATIONMENU" );
	$template->setVariable( "PAGE", "index.html" );
	$template->setVariable( "TITLE", "Home" );
	$template->parseCurrentBlock();
	$template->setVariable( "PAGE", "about.php" );
	$template->setVariable( "TITLE", "About Fitness Log" );
	$template->parseCurrentBlock();
}

//Show the user's Dashboard page.
$template->show();
?>