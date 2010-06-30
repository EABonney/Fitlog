<?php
/*
Plugin Name: Fitness Log 
Plugin URI: http://www.ericbonney.com
Description: Fitness training log plugin, designed mostly for triathlon training
Version: .1a
Author: Eric A. Bonney
Author URI: http://www.ericbonney.com
*/

/* Copyright 2009 Eric A. Bonney (eric@ericbonney.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
*/

// Create the tables.
// Table to hold just the main swim, bike, run workouts.	
$table = $wpdb->prefix . "flmain";
if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table )
{
	$structure = "CREATE TABLE $table (
		plan_type VARCHAR(1) NOT NULL,
		sbr_type VARCHAR(1) NOT NULL,
		duration TIME NOT NULL,
		seconds INT NOT NULL,
		distance FLOAT(6,2) NOT NULL,
		pace FLOAT(4,2),
		workout_date DATE NOT NULL,
		time_of_day TIME DEFAULT '00:00:00',
		min_hr INT,
		avg_hr INT,
		max_hr INT,
		notes VARCHAR(500),
		user_id BIGINT NOT NULL,
		cals_burned INT,
		avg_rpms FLOAT(4,2),

		PRIMARY KEY(plan_type, sbr_type, workout_date, user_id)
		);";

	if( $wpdb->query($structure) === false )
		add_option("wpfitnesslog_error", $wpdb->last_query);

	$wpdb->flush();	
}
	
// Table to hold the overall notes for the day.
$table = $wpdb->prefix . "flblog";
if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table )
{
	$structure = "CREATE TABLE $table (
		blog_date DATE NOT NULL,			
		user_id BIGINT NOT NULL,
		notes VARCHAR(5000),

		PRIMARY KEY(user_id, blog_date)
		);";

	if( $wpdb->query($structure) === false )
		add_option("wpfitnesslog_error", $wpdb->last_query);
	
	$wpdb->flush();			
}

// Table to hold the actual strenth training workouts performed on a given day.	
$table = $wpdb->prefix . "flstrength";
if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table )
{	
	$structure = "CREATE TABLE $table (
		exercises_id INT NOT NULL AUTO_INCREMENT,
		workout_date DATE NOT NULL,
		time_of_day TIME NOT NULL,
		duration TIME NOT NULL,
		user_id BIGINT NOT NULL,
		notes VARCHAR (256),

		PRIMARY KEY(exercises_id, workout_date, time_of_day, user_id)
		);";
	
	if( $wpdb->query($structure) === false )
		add_option("wpfitnesslog_error", $wpdb->last_query);

		$wpdb->flush();	
}
	
// Table to hold the different types of strength training exercises possible
$table = $wpdb->prefix . "flexercise_type";
if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table )
{	
	$structure = "CREATE TABLE $table (
		exercise_type_id INT NOT NULL AUTO_INCREMENT,
		category VARCHAR(25) NOT NULL,
		user_id BIGINT NOT NULL,
		excercise VARCHAR(25) NOT NULL,

		PRIMARY KEY(exercise_type_id, user_id)
		);";

	if( $wpdb->query($structure) === false )
		add_option("wpfitnesslog_error", $wpdb->last_query);

		$wpdb->flush();	
}

// Table to hold the exercise done in each strength training workout. 	
$table = $wpdb->prefix . "flexercises";
if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table )
{
	$structure = "CREATE TABLE $table (
		exercises_id INT NOT NULL,
		exercise_type_id INT NOT NULL,
		sets INT DEFAULT 0,
		reps INT DEFAULT 0,
		weight INT DEFAULT 0,
		user_id BIGINT NOT NULL,

		PRIMARY KEY(exercises_id, exercise_type_id, user_id)
		);";
	
	if( $wpdb->query($structure) === false )
		add_option("wpfitnesslog_error", $wpdb->last_query);

		$wpdb->flush();
}

// Table to hold other activities done.
$table = $wpdb->prefix . "ftotheractivities";
if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table )
{
	$structure = "CREATE TABLE $table (
		workout_date DATE NOT NULL,
		time_of_day TIME NOT NULL,
		activity VARCHAR(30) NOT NULL,
		duration TIME,
		user_id BIGINT NOT NULL,
	
		PRIMARY KEY(workout_date, time_of_day, user_id)
		);";	

	if( $wpdb->query($structure) === false )
		add_option("wpfitnesslog_error", $wpdb->last_query);
}

?>