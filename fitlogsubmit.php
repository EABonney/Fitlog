<?php
/* File: 	fitlogsubmit.php
   Desciption:	Implementation file for the actual submitting and inserting of workouts.
   Author:	Eric A. Bonney
   Date:	January 24, 2009
   Updated:	February 17, 2009
		October 3, 2009 - redid the script to work as stand alone application. Removed
				  all Wordpress info.
		December 15, 2009 - Fixed a bug related to the creation of bike records. Was
				    passing $avg_rpms[$i] originally, but the variable was not
				    an array, changed it to pass only $avg_rpms.
*/
require_once "includes/db.inc";
require_once "fitlogfunc.php";

session_start();

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

$userID = getUserID( $connection );
$error = array( false, false, false, false );
$plan_type = $_POST["plantype"];
$activity = array( "s", "b", "r" );
$entered = array( false, false, false, false );

// for the element arrays defaults are as follows:
// 0 = swim
// 1 = bike
// 2 = run
// 3 = strength
//
// example: 
//	$workout_date[0] = workoutdate for swim
//	$workout_date[1] = workoutdate for bike
//	$workout_date[2] = workoutdate for run
//	$workout_date[3] = workoutdate for strength
// Get the data entered and validate it.
$workout_date = array( $_POST["s_workoutdate"], $_POST["b_workoutdate"], $_POST["r_workoutdate"], $_POST["s_workoutdate"] );
$workout_time = array( $_POST["s_workouttime"], $_POST["b_workouttime"], $_POST["r_workouttime"], $_POST["str_workouttime"] );
$duration = array( $_POST["s_duration"], $_POST["b_duration"], $_POST["r_duration"], $_POST["str_duration"] );
$seconds = array( convert_time_seconds($duration[0]), convert_time_seconds($duration[1]), convert_time_seconds($duration[2]), convert_time_seconds($duration[3]) );
$distance = array( $_POST["s_distance"], $_POST["b_distance"], $_POST["r_distance"], 0 );
$notes = array( $_POST["s_notes"], $_POST["b_notes"], $_POST["r_notes"], $_POST["str_notes"] );
$min_hr = array( $_POST["s_min_hr"], $_POST["b_min_hr"], $_POST["r_min_hr"], 0 );
$avg_hr = array( $_POST["s_avg_hr"], $_POST["b_avg_hr"], $_POST["r_avg_hr"], 0 );
$max_hr = array( $_POST["s_max_hr"], $_POST["b_max_hr"], $_POST["r_max_hr"], 0 );
$avg_rpms = $_POST["avg_rpms"];
$calsburned = array( $_POST["s_calsburned"], $_POST["b_calsburned"], $_POST["r_calsburned"], 0 );
$updatingswim = $_POST["updatingswim"];
$updatingbike = $_POST["updatingbike"];
$updatingrun = $_POST["updatingrun"];
$updatingblognotes = $_POST["updatingblognotes"];
$blog_notes = $_POST["blog_notes"];

for($i=0; $i<3; $i++)
{
	// Validate data.
	if( !validate_date( $workout_date[$i] ) )
		$error[$i] = true;

	if( !validate_time( $workout_time[$i] ) )
		$error[$i] = true;

	if( !validate_time( $duration[$i] ) )
		$error[$i] = true;

	if( !validate_distance( $distance[$i] ) )
		$error[$i] = true;

	if( !validate_notes( $notes[$i] ) )
		$error[$i] = true;

	if( !validate_heartrate( $min_hr[$i] ) )
		$error[$i] = true;

	if( !validate_heartrate( $avg_hr[$i] ) )
		$error[$i] = true;

	if( !validate_heartrate( $max_hr[$i] ) )
		$error[$i] = true;

	if( $i == 1 )
	{
		if( !validate_rpms( $avg_rpms ) )
			$error[$i] = true;
	}

	if( !validate_calories( $calsburned[$i] ) )
		$error[$i] = true;
}

// Calculate the correct pace
if( !empty( $distance[0] ) && !empty( $duration[0] ) )
	$pace[0] = swimpace( $second[0], $distance[0] );

if( !empty( $distance[1] ) && !empty($duration[1] ) )
	$pace[1] = bikepace( $seconds[1], $distance[1] );

if( !empty( $distance[2] ) && !empty($duration[2] ) )
	$pace[2] = runpace( $seconds[2], $distance[2] );

// If we don't have any errors then go ahead and insert the new record(s) into the table.
for($i=0; $i<3; $i++)
{
	switch ( $i )
	{
		case 0:
		{
			if( !$error[0] )
			{
				if( $updatingswim )
				{
					$query = "UPDATE flmain" . " SET plan_type='" . $plan_type . "', sbr_type='" . $activity[$i] . "', duration='" . $duration[$i] . "', seconds=" . $seconds[$i] . ", distance='" . $distance[$i] . "', pace='" . $pace[$i] . "', min_hr=" . $min_hr[$i] . ", avg_hr=" . $avg_hr[$i] . ", max_hr=" . $max_hr[$i] . ", notes='" . $notes[$i] . "', user_id={$userID}, cals_burned=" . $calsburned[$i] . " WHERE workout_date='" . $workout_date[$i] . "' AND time_of_day='" . $workout_time[$i] . "'";
				}
				else
				{
					$query = "INSERT INTO flmain (plan_type, sbr_type, duration, seconds, distance, pace, workout_date, time_of_day, min_hr, avg_hr, max_hr, notes, user_id, cals_burned) VALUES ( '$plan_type', '$activity[$i]', '$duration[$i]', $seconds[$i], $distance[$i], $pace[$i], '$workout_date[$i]', '$workout_time[$i]', $min_hr[$i], $avg_hr[$i], $max_hr[$i], '$notes[$i]', {$userID}, $calsburned[$i])";
				}
			}
			break;
		}
		case 1:
		{
			if ( !$error[1] )
			{
				if( $updatingbike )
				{
					$query = "UPDATE flmain SET plan_type='" . $plan_type . "', sbr_type='" . $activity[$i] . "', duration='" . $duration[$i] . "', seconds=" . $seconds[$i] . ", distance='" . $distance[$i] . "', pace='" . $pace[$i] . "', min_hr=" . $min_hr[$i] . ", avg_hr=" . $avg_hr[$i] . ", max_hr=" . $max_hr[$i] . ", notes='" . $notes[$i] . "', user_id={$userID}, cals_burned=" . $calsburned[$i] . ", avg_rpms=" . $avg_rpms . " WHERE workout_date='" . $workout_date[$i] . "' AND time_of_day='" . $workout_time[$i] . "'";
				}
				else
				{
					$query = "INSERT INTO flmain (plan_type, sbr_type, duration, seconds, distance, pace, workout_date, time_of_day, min_hr, avg_hr, max_hr, notes, user_id, cals_burned, avg_rpms) VALUES ('$plan_type','$activity[$i]','$duration[$i]', $seconds[$i],$distance[$i],$pace[$i],'$workout_date[$i]','$workout_time[$i]',$min_hr[$i],$avg_hr[$i],$max_hr[$i],'$notes[$i]',$userID,$calsburned[$i], $avg_rpms)";
				}
			}
			break;
		}
		case 2:
		{
			if( !$error[2] )
			{
				if( $updatingrun )
				{
					$query = "UPDATE flmain" . " SET plan_type='" . $plan_type . "', sbr_type='" . $activity[$i] . "', duration='" . $duration[$i] . "', seconds=" . $seconds[$i] . ", distance='" . $distance[$i] . "', pace='" . $pace[$i] . "', min_hr=" . $min_hr[$i] . ", avg_hr=" . $avg_hr[$i] . ", max_hr=" . $max_hr[$i] . ", notes='" . $notes[$i] . "', user_id={$userID}, cals_burned=" . $calsburned[$i] . " WHERE workout_date='" . $workout_date[$i] . "' AND time_of_day='" . $workout_time[$i] . "'";
				}
				else
				{
					$query = "INSERT INTO flmain (plan_type, sbr_type, duration, seconds, distance, pace, workout_date, time_of_day, min_hr, avg_hr, max_hr, notes, user_id, cals_burned) VALUES ( '$plan_type', '$activity[$i]', '$duration[$i]', $seconds[$i], $distance[$i], $pace[$i], '$workout_date[$i]', '$workout_time[$i]', $min_hr[$i], $avg_hr[$i], $max_hr[$i], '$notes[$i]', {$userID}, $calsburned[$i])";
				}
			}
			break;
		}
	}

	// Need a better failure handler here!
	if( ! (@ mysql_query( $query, $connection ) ) )
	{
		header( "Location: workoutview.php" );
	}
	else
		$entered[$i] = true;
}

// Insert any blog entry that we might have made now into the flblog table.
if( validate_notes( $blog_notes ) )
{
	if( $updatingblognotes )
		$query = "UPDATE flblog SET notes='" . $blog_notes . "' WHERE blog_date='" . $workout_date[0] . "' AND user_id=" . $userID;
	else
		$query = "INSERT INTO flblog (blog_date, user_id, notes) VALUES ('$workout_date[0]', $userID, '$blog_notes')";

	// Need a better failure handler here!
	if( ! (@ mysql_query( $query, $connection ) ) )
	{
		header( "Location: workoutview.php" );
	}
}	

// Insert or update the table flstrength now.
//See if the user entered a time of day, if so then we assume they want to enter a new record. If not then do nothing.
/*if( !empty( $workout_time[3] ) )
{
	// First validate the data
	if( !validate_date( $workout_date[3] ) )
		$error[3] = true;
	if( !validate_time( $duration[3] ) )
		$error[3] = true;
	if( !validate_notes( $notes[3] ) )
		$error[3] = true;

	if( !$error[3] )
	{
		if( $updating )
			$query = "UPDATE flstrength SET workout_date='" . $workout_date[3] . "', time_of_day='" . $workout_time[3] . "', seconds=" . $seconds[3] . ", duration='" . $duration[3] . "', notes='" . $notes[3] . "', user_id=" . $userID;
		else
			$query = "INSERT INTO flstrength (exercises_id, workout_date, time_of_day, seconds, duration, notes, user_id) VALUES (NULL, '$workout_date[3]', '$workout_time[3]', $seconds[3], '$duration[3]', '$notes[3]', $userID)";

		// Need a better failure handler here!
		if( ! (@ mysql_query( $query, $connection ) ) )
		{
			header( "Location: history.php" );
		}

		$entered[3] = true;
	}
}*/

if( ($error[0] && $entered[0]) || ($error[1] && $entered[1]) || ($error[2] && $entered[2]) || ($error[3] && $entered[3]) )
{
	header( "Location: workoutview.php" );
}
else
{
	header( "Location: workoutview.php" );
}
/************************************** END OF THE MAIN SCRIPT **********************************************/
?>

<?php
/******************************** Validation routines for the data *****************************************/

// Just make sure that the date is in the format YYYY-MM-DD and that it has actually been entered.
// Function retuns true if the date is valid and false if it is not valid.
function validate_date( $date_entered )
{
	if( empty( $date_entered ) )
		return false;
	elseif( !ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2})", $date_entered, $date_array ) )
		return false;
	elseif( !checkdate( $date_array[2], $date_array[3], $date_array[1] ) )
		return false;
	
	// If we have reached this point then the date is valid, so return true.
	return true;
}

// Make sure that the duration has been entered, that it is in HH:MM:SS format.
// HH < 23, MM < 59, SS < 59
// Function returns true if the duration is valid and false if it is not valid
function validate_time( $time_entered )
{
	if( empty( $time_entered ) )
		return false;
	elseif ( !ereg( "^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$", $time_entered, $time_array ) )
		return false;

	// If we have reached this point then the duration is valid, so return true.
	return true;
}

// Make sure that the distance has been entered and that it is reasonable.
// Function returns true if the duration is valid and false if it is not valid
function validate_distance( $distance_entered )
{
	if( empty( $distance_entered ) )
		return false;
	elseif( !ereg( '^[0-9]{1,5}[.]?[0-9]{0,2}$', $distance_entered ) )
		return false;

	// If we have reached this point then the distance is valid, so return true.
	return true;
}

// Make sure that the pace is a valid number.
// Function returns true if the pace is valid and false if it is not valid.
function validate_pace( $pace_entered )
{
	if( empty( $pace_entered ) )
		return true;
	elseif( !ereg( '^[0-9]{1,2}[.]?[0-9]{0,2}$', $pace_entered ) )
		return false;

	// If we have reached this point then the pace is valid, so return true.
	return true;
}

// Just check to make sure that the length of the notes field is less than 500 characters.
// Function return true if the length test is valid and false if it is not valid.
function validate_notes( $notes_entered )
{
	if( strlen( $notes_entered ) > 500 )
		return false;

	// If we are here then the notes must be less than 500 characters so return true.
	return true;
}

// Check to make sure that the heart rate is in a valid range. We assume between 40 - 300.
// Function returns true if the heart rate enteres is between this range and false otherwise.
function validate_heartrate( $hr_entered )
{
	$intHR = intval( $hr_entered );

	if( !is_numeric( $hr_entered ) )
		return false;

	// If we are here then the heart rate must be within the correct range.
	return true;
}	

// Check to make sure that the calories entered is a valid number only. No ranges specified.
// Return true if it is a valid number and false otherwise.
function validate_calories( $calories_entered )
{
	$intNum = intval( $calories_entered );

	if( !is_numeric( $intNum) )
		return false;

	// If we are here then calories burned is a valid number so return true.
	return true;
}

function validate_rpms( $rpms_entered )
{
	if( !is_numeric( $rpms_entered ) )
		return false;

	return true;
}

function check_entered( $c_duration, $c_distance, $c_workoutdate, $c_timeofday )
{
	if( empty( $c_duration ) || empty( $c_distance ) || empty( $c_workoutdate ) || empty( $c_timeofday ) )
		return false;
	else
		return true;
}
?>