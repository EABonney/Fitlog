<?php
/* File: 	strengthview.php
   Desciption:	Implementation file for the Strength workouts overall view.
   Author:	Eric A. Bonney
   Date:	December 16, 2009
   Updated:	
*/

require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "Date.php";
require_once 'Calendar/Month/Weekdays.php';
require_once "fitlogfunc.php";

$display = $_GET[ "display" ];

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "strengthtraining.tpl", true, true );

session_start();

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	$delAll = FALSE;
	$userID = getUserID( $connection );
	$yearmoday = explode( "-", $_SESSION["nav_month"] );

	// See if the users is trying to display a month other than the current month.
	if( $yearmoday != NULL )
	{
		$display_mo = new Date();
		$display_mo->setDayMonthYear( $yearmoday[2], $yearmoday[1], $yearmoday[0] );
	}
	else
		$display_mo = new Date();

	//Set the date to the first day of the month.
	$display_mo->setDayMonthYear( 1, $display_mo->getMonth(), $display_mo->getYear() );
	
	// Get the previous day from the displayed month.
	$prevDay = $display_mo->getPrevDay();
	$prevDay->setDayMonthYear( 1, ($prevDay->getMonth()), $prevDay->getYear() );

	// Get the next day in from the diplayed month.
	$display_mo->setDayMonthYear( $display_mo->getDaysInMonth(), $display_mo->getMonth(), $display_mo->getYear() );
	$nextDay = $display_mo->getNextDay();

	$template->setCurrentBlock( "NAVIGATION" );
	$template->setVariable( "MONTH", "{$display_mo->getMonthName()} {$display_mo->getYear()}" );
	$template->setVariable( "PREVIOUS", $prevDay->format( "%Y-%m-%d" ) );
	$template->setVariable( "NEXT", $nextDay->format( "%Y-%m-%d" ) );
	$template->setVariable( "TYPE", $display );
	setSelectOption( $display, $template );
	$template->parseCurrentBlock();

	$Month = new Calendar_Month_Weekdays( $display_mo->getYear(), $display_mo->getMonth(), 1 );

	$Month->build();

	$template->setCurrentBlock( "WEEK" );
	while( $Day = $Month->fetch() )
	{
		// Set the delete all button to off by default.
		$delAll = FALSE;

		if( $Day->isFirst() )
		{
			// Set the begining block area to {ONE}
			// and set the current block
			$currentDay = 1;
		}

		$curyear = strval( $Day->thisYear() );
		$curmonth = strval( $Day->thisMonth() );
		$curday = strval( $Day->thisDay() );
		$txtDay = new Date();
		$txtDay->setDayMonthYear( $curday, $curmonth, $curyear );

		$str = getWorkoutRow($txtDay->format( "%Y-%m-%d" ), $userID, $display, $connection );

		//Set which plan type the user is displaying in the date cells
		if( isset( $display ) )
			$template->setVariable( "TYPE", $display );
		else
			$template->setVariable( "TYPE", "Actual" );

		switch ( $currentDay )
		{
			case 1:
				$template->setVariable( "ONE", $Day->thisDay() );
				$template->setVariable( "UPDATE1", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR1", "S" );
					$template->setVariable( "STRDUR1", $str["duration"] );
					$template->setVariable( "STRDELDATE1", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL1", "x");
					$template->setVariable( "STRID1", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE1", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL1", "x" );
				}
				break;
			case 2:
				$template->setVariable( "TWO", $Day->thisDay() );
				$template->setVariable( "UPDATE2", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR2", "S" );
					$template->setVariable( "STRDUR2", $str["duration"] );
					$template->setVariable( "STRDELDATE2", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL2", "x");
					$template->setVariable( "STRID2", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE2", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL2", "x" );
				}
				break;
			case 3:
				$template->setVariable( "THREE", $Day->thisDay() );
				$template->setVariable( "UPDATE3", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR3", "S" );
					$template->setVariable( "STRDUR3", $str["duration"] );
					$template->setVariable( "STRDELDATE3", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL3", "x");
					$template->setVariable( "STRID3", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE3", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL3", "x" );
				}
				break;
			case 4:
				$template->setVariable( "FOUR", $Day->thisDay() );
				$template->setVariable( "UPDATE4", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR4", "S" );
					$template->setVariable( "STRDUR4", $str["duration"] );
					$template->setVariable( "STRDELDATE4", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL4", "x");
					$template->setVariable( "STRID4", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE4", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL4", "x" );
				}	
				break;
			case 5:
				$template->setVariable( "FIVE", $Day->thisDay() );
				$template->setVariable( "UPDATE5", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR5", "S" );
					$template->setVariable( "STRDUR5", $str["duration"] );
					$template->setVariable( "STRDELDATE5", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL5", "x");
					$template->setVariable( "STRID5", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE5", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL5", "x" );
				}
				break;
			case 6:
				$template->setVariable( "SIX", $Day->thisDay() );
				$template->setVariable( "UPDATE6", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR6", "S" );
					$template->setVariable( "STRDUR6", $str["duration"] );
					$template->setVariable( "STRDELDATE6", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL6", "x");
					$template->setVariable( "STRID6", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE6", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL6", "x" );
				}
				break;
			case 7:
				$template->setVariable( "SEVEN", $Day->thisDay() );
				$template->setVariable( "UPDATE7", $txtDay->format( "%Y-%m-%d" ) );
				if( $str )
				{
					$template->setVariable( "STR7", "S" );
					$template->setVariable( "STRDUR7", $str["duration"] );
					$template->setVariable( "STRDELDATE7", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "STRDEL7", "x");
					$template->setVariable( "STRID7", $str["exercises_id"] );
					$delAll = TRUE;
				}
				// See if we need to display the delete all icon.
				if( $delAll )
				{
					$template->setVariable( "DELALLDATE7", $txtDay->format( "%Y-%m-%d" ) );
					$template->setVariable( "DELALL7", "x" );
				}
				break;
		}

		// Move to the next block on the calendar view.
		$currentDay = $currentDay + 1;

		if( $Day->isLast() )
		{
			// parse the block
			$template->parseCurrentBlock();
			$template->setCurrentBlock( "WEEK" );
		}	
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

//Show the user the Month View page.
$template->show();

/******************************* Helper Functions ***********************************************/
function getWorkoutRow( $date, $userID, $display_type, $connection )
{
	// See what type the user wants to display, actual or planned.
	switch ( $display_type )
	{
		case "Actual":
			$type = 'a';
			break;
		case "Planned":
			$type = 'p';
			break;
		default:
			$type = 'a';
			break;
	}

	// Get the current day's workouts
	$query = "SELECT duration, exercises_id FROM flstrength WHERE workout_date='" . $date . "' AND plan_type='" . $type . "' AND user_id={$userID}";

	// Run the queries to get results.
	$results = @ mysql_query( $query, $connection );

	return mysql_fetch_array( $results );
}

function setSelectOption( $display, $template )
{
	switch ( $display )
	{
		case "Actual":
			$template->setVariable( "SELECTEDA", 'selected="selected"' );
			break;
		case "Planned":
			$template->setVariable( "SELECTEDP", 'selected="selected"' );
			break;
		default:
			$template->setVariable( "SELECTEDA", 'selected="selected"' );
			break;
	}
}
?>