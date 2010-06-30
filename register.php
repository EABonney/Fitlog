<?php
	require_once "HTML/Template/ITX.php";

	// Create a new template, and specify that the template files are
	// in the same directory as the as the php files.
	$template = new HTML_Template_IT( "./templates" );
	
	// Load the registration template file
	$template->loadTemplatefile( "registration.tpl", true, true );
	$template->setVariable( "ERROR_USERNAME", "" );
	$template->setVariable( "ERROR_EMAIL", "" );
	$template->setVariable( "ERROR_PASSWORD", "" );
	$template->setVariable( "ERROR_CONFIRM", "" );

	$template->show();
?>