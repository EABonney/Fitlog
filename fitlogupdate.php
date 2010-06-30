<?php
/* File: 	fitlogupdate.php
   Desciption:	Implementation file for the updating/deleting of workouts.
   Author:	Eric A. Bonney
   Date:	February 6, 2009
   Updated:	February 17, 2009
*/
require_once( '../../../wp-blog-header.php' );
require_once( '../../../wp-load.php');

global $wpdb;
global $userdata;
get_currentuserinfo();

$date_edited = $_GET["edit_date"];

//Start a new session or find an existing one.
session_start();

// Let's pull the day that the user has selected to edit/update from the flmain table.
$query = "SELECT * FROM " . $wpdb->prefix . "flmain WHERE workout_date='" . $date_edited . "' AND user_id=" . $userdata->ID;

// Let's pull the day that the user has selected to edit/update from the flblog table
$query2 = "SELECT * FROM " . $wpdb->prefix . "flblog WHERE blog_date='" . $date_edited . "' AND user_id=" . $userdata->ID;

// Let's pull the day that the user has selected to edit/update from the flstrength table
$query3 = "SELECT * FROM " . $wpdb->prefix . "flstrength WHERE workout_date='" . $date_edited . "' AND user_id=" . $userdata->ID;

// Run the two queries.
$results = $wpdb->get_results( $query, ARRAY_A );
$results_blog = $wpdb->get_results( $query2, ARRAY_A );
$results_strength = $wpdb->get_results( $query3, ARRAY_A );

$_SESSION["date_updated"] = $date_edited;

//If we have any data,then store it in a session, if not just set the session for the workout date selected.
if( $results )
{
	// Now store the day to be edited inside a session
	foreach( $results as $row )
	{
		switch( $row["sbr_type"] )
		{
			case "s":
				$_SESSION["s_workoutdate"] = $row["workout_date"];
				$_SESSION["s_workouttime"] = $row["time_of_day"];
				$_SESSION["s_duration"] = $row["duration"];
				$_SESSION["s_distance"] = $row["distance"];
				$_SESSION["s_pace"] = $row["pace"];
				$_SESSION["s_minhr"] = $row["min_hr"];
				$_SESSION["s_avghr"] = $row["avg_hr"];
				$_SESSION["s_maxhr"] = $row["max_hr"];
				$_SESSION["s_calsburned"] = $row["cals_burned"];
				$_SESSION["s_notes"] = $row["notes"];
				$_SESSION["s_sbr_type"] = "s";
				$_SESSION["s_plan_type"] = "a";
				break;
			case "b":
				$_SESSION["b_workoutdate"] = $row["workout_date"];
				$_SESSION["b_workouttime"] = $row["time_of_day"];
				$_SESSION["b_duration"] = $row["duration"];
				$_SESSION["b_distance"] = $row["distance"];
				$_SESSION["b_pace"] = $row["pace"];
				$_SESSION["b_avgrpms"] = $row["avg_rpms"];
				$_SESSION["b_minhr"] = $row["min_hr"];
				$_SESSION["b_avghr"] = $row["avg_hr"];
				$_SESSION["b_maxhr"] = $row["max_hr"];
				$_SESSION["b_calsburned"] = $row["cals_burned"];
				$_SESSION["b_notes"] = $row["notes"];
				$_SESSION["b_sbr_type"] = "b";
				$_SESSION["s_plan_type"] = "a";
				break;
			case "r":
				$_SESSION["r_workoutdate"] = $row["workout_date"];
				$_SESSION["r_workouttime"] = $row["time_of_day"];
				$_SESSION["r_duration"] = $row["duration"];
				$_SESSION["r_distance"] = $row["distance"];
				$_SESSION["r_pace"] = $row["pace"];
				$_SESSION["r_minhr"] = $row["min_hr"];
				$_SESSION["r_avghr"] = $row["avg_hr"];
				$_SESSION["r_maxhr"] = $row["max_hr"];
				$_SESSION["r_calsburned"] = $row["cals_burned"];
				$_SESSION["r_notes"] = $row["notes"];
				$_SESSION["r_sbr_type"] = "r";
				$_SESSION["s_plan_type"] = "a";
				break;
		}
	}

	$_SESSION["updating"] = true;
}
else
	$_SESSION["updating"] = false;

if( $results_blog )
{
	// Now store the day to be edited inside a session
	foreach( $results_blog as $row )
		$_SESSION["blog_notes"] = $row["notes"];

	$_SESSION["updating"] = true;
}

if( $results_strength )
{
	// Now store the day to be edited inside a session
	foreach( $results_strength as $row )
	{
		$_SESSION["str_exercises_id"] = $row["exercises_id"];
		$_SESSION["str_workoutdate"] = $row["workout_date"];
		$_SESSION["str_workouttime"] = $row["time_of_day"];
		$_SESSION["str_duration"] = $row["duration"];
		$_SESSION["str_notes"] = $row["notes"];
	}

	$_SESSION["updating"] = true;
}

// re-direct to the actual form.
$url = get_bloginfo(wpurl) . "/wp-admin/admin.php?page=fitnesslog-workouts";
wp_redirect($url);
?>