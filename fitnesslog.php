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

require 'fitlogfunc.php';
require_once 'Date.php';
require_once 'Image/Graph.php';

/**************************************************** Begin Fitness Log Plugin code ************************************************************/
function fitnesslog_activate()
{
	global $wpdb;

	// Create the page used for the actual tracking.
	$pages = get_page_by_title("Fitness Log"); 
	if( empty( $pages ) )
	{
		$page = array();
		$page['post_title'] = 'Fitness Log';
		$page['post_type'] = 'page';
		$page['post_status'] = 'publish';
		// Insert the page into the database
		$pageID = wp_insert_post( $page );
		add_option("FitnessLog_pageID", $pageID);
	}
	
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
};

function fitnesslog_build_history()
{
	global $wp_query;
	$page = get_page_by_title("Fitness Log");

	if( $wp_query->post->ID == $page->ID )
		add_filter( 'the_content', 'fitnesslog_format_history' );
}

function fitnesslog_format_history( $text )
{
	// Get the currently displayed day for the user and get today.
	$today = new Date();
	$yearmoday = explode( "-", $_SESSION["nav_month"] );

	// See if the users is trying to display a month other than the current month.
	if( $yearmoday != NULL )
	{
		$display_date = new Date();
		$display_date->setDayMonthYear( $yearmoday[2], $yearmoday[1], $yearmoday[0] );
	}
	else
		$display_date = new Date();

	$nav_date = new Date($display_date);

	//Get two weeks from now.
	$last_day = new Date($display_date);
	$last_day->addDays(-14);

	if( is_user_logged_in() )
	{
		// Start the table.
		$history = '<p><table width="100%">';
		
		// Setup navigation links.
		$nav_date->addDays(-14);
		$nav_prev = '<tr><th><a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/ftnav.php?month=' . $nav_date->format( "%Y-%m-%d" ) .  '&source=history">Previous</a></th><th>&nbsp&nbsp&nbsp&nbsp&nbsp';
		echo $nav_prev;
		$nav_cur = '<a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/ftnav.php?month=' . $today->format( "%Y-%m-%d" ) . '&source=history">Today</a>&nbsp&nbsp&nbsp&nbsp&nbsp';
		echo $nav_cur;
		$nav_date->addDays(28);
		$nav_next = '<a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/ftnav.php?month=' . $nav_date->format( "%Y-%m-%d" ) . '&source=history">Next</a></th></tr>';
		echo $nav_next;
	
		// pass in a row to the function to format the daily logs.
		$history= $history . form_days( $display_date->format( '%Y-%m-%d' ), $last_day->format( '%Y-%m-%d' ) );
 	
		//close off the table.
		$history = $history . "</table></p>";
	}
	else
		$history = "<p>Please create an account if you wish to use the Fitness Log area of the website. It is free so why not do it?!</p>";

	// Destroy the session
	session_destroy();
	
	// return the actual content of the page.
	return $history;
}

function fitnesslog_widget_registration()
{
	// Register our sidebar widgets.
	register_sidebar_widget( 'FT Log Weekly', 'fitnesslog_widget_summary_weekly' );
	register_sidebar_widget( 'FT Log Monthly', 'fitnesslog_widget_summary_monthly' );
	register_sidebar_widget( 'FT Log Prior Month', 'fitnesslog_widget_summary_prior_one' );
	register_sidebar_widget( 'FT Log Prior Month Two', 'fitnesslog_widget_summary_prior_two' );
	register_sidebar_widget( 'FT Log Prior Month Three', 'fitnesslog_widget_summary_prior_three' );
	register_sidebar_widget( 'FT Log Annual', 'fitnesslog_widget_summary_annual' );
	register_sidebar_widget( 'FT Log Prior Years', 'fitnesslog_widget_summary_prior_years' );
	register_sidebar_widget( 'FT Graphs', 'fitnesslog_widget_graphs' );
}

function fitnesslog_widget_summary_weekly( $args )
{
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo 'Weekly Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "w" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "w" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "w" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "w" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "w" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "w" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "w" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "w" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_summary_monthly( $args )
{
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo 'Monthly Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "m" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "m" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "m" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "m" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "m" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "m" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "m" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "m" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_summary_prior_one( $args )
{
	$month = new Date();
	$month->setDayMonthYear( 1, $month->getMonth(), $month->getYear() );
	$display = $month->getPrevDay();
	
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo $display->format( "%b %Y" ); echo ' Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "pm" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "pm" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "pm" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "pm" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "pm" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "pm" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "pm" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "pm" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_summary_prior_two( $args )
{
	$month = new Date();
	$month->setDayMonthYear( 1, $month->getMonth(), $month->getYear() );
	$display = $month->getPrevDay();
	$month->setDayMonthYear( 1, $display->getMonth(), $display->getYear() );
	$display = $month->getPrevDay();
	
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo $display->format( "%b %Y" ); echo ' Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "ppm" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "ppm" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "ppm" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "ppm" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "ppm" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "ppm" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "ppm" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "ppm" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_summary_prior_three( $args )
{
	$month = new Date();
	$month->setDayMonthYear( 1, $month->getMonth(), $month->getYear() );
	$display = $month->getPrevDay();
	$month->setDayMonthYear( 1, $display->getMonth(), $display->getYear() );
	$display = $month->getPrevDay();
	$month->setDayMonthYear( 1, $display->getMonth(), $display->getYear() );
	$display = $month->getPrevDay();
	
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo $display->format( "%b %Y" ); echo ' Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "pppm" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "pppm" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "pppm" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "pppm" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "pppm" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "pppm" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "pppm" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "pppm" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_summary_annual( $args )
{
	$display = new Date();
	
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo $display->format( "%Y" ); echo ' Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "a" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "a" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "a" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "a" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "a" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "a" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "a" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "a" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_summary_prior_years( $args )
{
	$display = new Date();
	$display->setDayMonthYear( $display->getDay(), $display->getMonth(), $display->getYear() - 1 );
	
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo $display->format( "%Y" ); echo ' Totals';
	echo $after_title;
	echo '<ul>';

	$result = get_summary_totals( "s", "py" );
	if( $result )
	{
		echo '<li>Swim: ';
		echo get_summary_totals( "s", "py" );
		echo '</li>';
	}

	$result = get_summary_totals( "b", "py" );
	if( $result )
	{
		echo '<li>Bike: ';
		echo get_summary_totals( "b", "py" );
		echo '</li>';
	}

	$result = get_summary_totals( "r", "py" );
	if( $result )
	{
		echo '<li>Run: ';
		echo get_summary_totals( "r", "py" );
		echo '</li>';
	}

	$result = get_summary_totals( "str", "py" );
	if( $result )
	{
		echo '<li>Strength: ';
		echo get_summary_totals( "str", "py" );
		echo '</li>';
	}

	echo '</ul>';
	echo $after_widget;
}

function fitnesslog_widget_graphs( $args )
{
	// Get the arguments passed to the widget.
	extract( $args );

	echo $before_widget;
	echo $before_title;
	echo 'Weekly Volume';
	get_weekly_volume_graph();
	echo '<img src="'; echo get_bloginfo('url'); echo '/wp-content/plugins/fitnesslog/graphs/wklygraph.png">';
	echo $after_title;
	echo $after_widget;
}
/***************************************** End Fitness Log Plugin Code *****************************************************************/

/****************************************** Begin Fitness Log Settings code *************************************************************/
function fitnesslog_addHeaderCode()
{
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/fitnesslog/fitlog.css" />' . "\n";
}

function fitnesslog_addJavaScript()
{
	if (function_exists('wp_enqueue_script'))
	{
		wp_enqueue_script('fitnesslog_script', get_bloginfo('wpurl') . '/wp-content/plugins/fitnesslog/scripts/fitlog_scripts.js', array('prototype'), '0.1');
		wp_enqueue_script('fitnesslog_script_googlemaps', 'http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAb9KpfEQqY_1OLDaRe5pophSRVq6TI5HwO14ly68M86soTMgp0BStrFgKO8Vvm_eLhAZQ6S8_MeNRMw&sensor=true', array('prototype'), '0.1');
	}
}

function fitnesslog_menu()
{
	add_menu_page('Fitness Log', 'Fitness Log', 0, __FILE__, 'fitnesslog_main_menu');
	add_submenu_page(__FILE__, 'Fitness Log', 'Settings', 8, 'fitnesslog-settings', 'fitnesslog_menu_settings');
	add_submenu_page(__FILE__, 'Fitness Log', 'Preferences', 0, 'fitnesslog-preferences', 'fitnesslog_menu_preferences');
	add_submenu_page(__FILE__, 'Fitness Log', 'Plan Workouts', 0, 'fitnesslog-workouts', 'fitnesslog_menu_workouts');
	add_submenu_page(__FILE__, 'Fitness Log', 'Exercises', 0, 'fitnesslog-exercises', 'fitnesslog_menu_exercises');
	add_submenu_page(__FILE__, 'Fitness Log', 'Routes', 0, 'fitnesslog-routes', 'fitnesslog_menu_routes');
	add_submenu_page(__FILE__, 'Fitness Log', 'Monthly Summary', 0, 'fitnesslog-logdata', 'fitnesslog_menu_logdata');
	add_submenu_page(__FILE__, 'Fitness Log', 'Annual Summaries', 0, 'fitnesslog-annual', 'fitnesslog_menu_annual');
	add_submenu_page(__FILE__, 'Fitness Log', 'Training Plans', 0, 'fitnesslog-plans', 'fitnesslog_menu_plans');
	add_submenu_page(__FILE__, 'Fitness Log', 'Export/Import Data', 0, 'fitnesslog-data', 'fitnesslog_menu_data');
	add_submenu_page(__FILE__, 'Fitness Log', 'Reports', 0, 'fitnesslog-reports', 'fitnesslog_menu_reports');
}

function fitnesslog_main_menu()
{
	//echo '<h2>Fitness Log</h>';
}

function fitnesslog_menu_settings()
{
	echo '<div class="wrap">';
	echo '<h2>Fitness Log Settings</h2>';	
	echo '<p> Here is where the settings will go for our plugin.</p>';
	echo '</div>';
}

function fitnesslog_menu_preferences()
{
	echo '<div class="wrap">';
	echo '<h2>Personal Preferences</h2>';	
	echo '<p> Here is where the settings will go for each individual user.</p>';
	echo '</div>';
}

function fitnesslog_menu_workouts()
{
	// First let's check to see if a session has been started and if any of the elements have been set.
	session_start();

	// If $_SESSION["date_updated"] is set go use that date, otherwise, set it to today's date.
	if( isset( $_SESSION["date_updated"] ) )
		$date = new Date( $_SESSION["date_updated"] );
	else
	{
		global $userdata;
		get_currentuserinfo();
		$date = new Date();
		$_SESSION["date_updated"] = $date->format( "%Y-%m-%d" );
		$result = get_daily_blognotes( $date->format( "%Y-%m-%d" ), $userdata->ID );

		if( $result )
		{
			$_SESSION["blog_notes"] = $result;
			$_SESSION["updating"] = true;
		}
	}

	// Print out the date so the user knows what date they are working with.
	echo '<div class="wrap">';
	echo '<h2>' . $date->format( "%A %B %d, %Y" ) . '</h2>';

	if( $_SESSION["updating"] )
		fitnesslog_create_form( true );
	else
		fitnesslog_create_form( false );
	
	echo '</div>';			//end of div=wrap
}

function fitnesslog_menu_exercises()
{
	echo '<div class="wrap">';
	echo '<h2>Exercises</h2>';
	echo '<form action="' . get_bloginfo( 'url' ) . '/wp-content/plugins/fitnesslog/ftlogexercisestype.php" method="post">';
	echo '<table>';
	echo '<tr><td>Exercise Category:</td>';
	echo '<td><input type="text" name="exercise_category" id="exercise_category" TABINDEX=1/></td></tr>';
	echo '<tr><td>Exercise description:</td>';
	echo '<td><input type="text" name="exercise_desc" id="exercise_desc" TABINDEX=2/></td></tr>';
	echo '</table>';
	echo '<p class="submit"><input type="submit" value="Save Exercise" /></p>';
	echo '</form>';

	// Print out the current exercises in a table for the user to see and edit or delete.
	list_exercises();
	echo '</div>';
}

function fitnesslog_menu_routes()
{
	echo '<div class="wrap">';
	echo '<h2>Routes</h2>';
	echo '<p> Generate maps for your routes via google maps.</p>';
	echo '<div id="map_canvas" style="width: 800px; height: 475px">';
	echo '<script>initialize("6061 Brofield Dr, Hamilton, OH")</script>';
	echo '</div>';
	echo '</div>';
}

function fitnesslog_menu_logdata()
{
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

	echo '<div class="wrap">';
	echo '<h2>' . $display_mo->getMonthName() . " " . $display_mo->getYear() . " Totals</h2>";
	$nav_prev = '<a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/ftnav.php?month=' . $prevDay->format( "%Y-%m-%d" ) . '&source=summary">Previous Month</a>';
	echo '<p>' . $nav_prev . '&nbsp&nbsp&nbsp&nbsp&nbsp';
	$nav_cur = '<a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/ftnav.php?source=summary">Current Month</a>';
	echo $nav_cur . '&nbsp&nbsp&nbsp&nbsp&nbsp';
	$display_mo->setDayMonthYear( 1, ($display_mo->getMonth() + 1), $display_mo->getYear() );
	$nav_next = '<a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/ftnav.php?month=' . $nextDay->format( "%Y-%m-%d" ) . '&source=summary">Next Month</a>';
	echo $nav_next . '</p>';
	create_history();
	echo '</div>';
}

function fitnesslog_menu_annual()
{
	global $wpdb;
	$table = $wpdb->prefix . "flmain";
	global $current_user;
	get_currentuserinfo();

	// Get the current date and then create the Jan 1 and Dec 31 date.
	$beg_date = new Date();
	$end_date = new Date();
	$beg_date->setDayMonthYear( 1, 1, $beg_date->getYear() );
	$end_date->setDayMonthYear( 31, 12, $end_date->getYear() );

	echo '<div class="wrap">';
	echo '<h2>Annual Totals</h2>';	
	echo '<table class="history">';
	echo '<tr><th>Year</th><th>Swimming</th><th>Biking</th><th>Running</th></tr>';

	$query = "SELECT user_id, SUM(distance) AS distance FROM " . $wpdb->prefix . "flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND plan_type='a'" . " AND user_id=" . $current_user->ID . " GROUP BY sbr_type ASC";

	$result = $wpdb->get_results( $query, ARRAY_A );

	while ( $result )
	{
		echo '<tr><td>'; echo $beg_date->format( "%Y" ); echo '</td>';

		$i = 1;
	
		foreach( $result as $row )
		{
			switch ( $i )
			{
				case 1:
					$bike = $row['distance'];
					break;
				case 2:
					$run = $row['distance'];
					break;
				case 3:
					$swim = $row['distance'];
					break;
			}

			$i = $i + 1;
		}

		echo '<td>'; echo $swim; echo ' yds</td><td>'; echo $bike; echo ' mi</td><td>'; echo $run; echo ' mi</td>';
		echo '</tr>';	
		
		// Attempt to move to the next previous year.
		$beg_date->setDayMonthYear( 1, 1, $beg_date->getYear() - 1 );
		$end_date->setDayMonthYear( 31, 12, $end_date->getYear() - 1 );

		$query = "SELECT user_id, SUM(distance) AS distance FROM " . $wpdb->prefix . "flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND plan_type='a'" . " AND user_id=" . $current_user->ID . " GROUP BY sbr_type ASC";

		$result = $wpdb->get_results( $query, ARRAY_A );
	}

	echo '</table>';
	echo '</div>';
}
function fitnesslog_menu_plans()
{
	echo '<div class="wrap">';
	echo '<h2>Training Plans</h2>';
	echo '<p>Here you will be able to load in your planned trainging for upcoming dates.</p>';
	echo '</div>';
}

function fitnesslog_menu_data()
{
	echo '<div class="wrap">';
	echo '<h2>Export/Import Training Data</h2>';
	echo '<form enctype="multipart/form-data" action="/wp-content/plugins/fitnesslog/fitlogupload.php" method="POST">';
	echo '<table>';
//	echo '<input type="hidden" name="MAX_FILE_SIZE" value="100000" />';
	echo '<tr><td>Plan type:</td><td><select name="plan_type" id="plan_type">';
	echo '<option>Actual</option>'; echo '<option>Planned</option>'; echo '</select></td>';
	echo '</table>';
	echo '<p>Choose a file to upload:<input name="uploadedfile" type="file" /></p>';
	echo '<p class="submit"><input type="submit" value="Upload File" /></p>';
	echo '</form>';

	// Start a session to see if we have any upload status to display
	session_start();
	$msg = $_SESSION["upload_status"];

	if( $msg )
		echo '<p><h3>' . $msg . '</h3></p>';

	session_destroy();
	echo '</div>';
}

function fitnesslog_menu_reports()
{
	echo '<div class="wrap">';
	echo '<h2>Reports</h2>';
	echo '</div>';
}

function fitnesslog_create_form( $updating )
{
	global $wpdb;
	$table = $wpdb->prefix . "flmain";
	global $current_user;
	get_currentuserinfo();
	
	echo '<form action="/wp-content/plugins/fitnesslog/fitlogsubmit.php" method="post">';
	echo '<table>';
	echo '<tr><td></td><td align=center><h2>Swimming</h2></td><td></td><td align=center><h2>Biking</h2></td><td></td><td align=center><h2>Running</h2></td></tr>';
	echo '<td><input type="hidden" name="s_workoutdate" id="s_workoutdate" TABINDEX=1';
	echo ' value="'; echo $_SESSION["date_updated"]; echo '"/></td><td></td>';
	echo '<td><input type="hidden" name="b_workoutdate" id="b_workoutdate" TABINDEX=10';
	echo ' value="'; echo $_SESSION["date_updated"]; echo '"/></td><td></td>';
	echo '<td><input type="hidden" name="r_workoutdate" id="r_workoutdate" TABINDEX=20';
	echo ' value="'; echo $_SESSION["date_updated"]; echo '"/></td></tr>';

	echo '<tr><td><label for="workouttime">Workout time <span class="small">(hh:mm:ss)</span>:</label></td>';
	echo '<td><input type="text" name="s_workouttime" id="s_workouttime" TABINDEX=2';
	echo ' value="'; echo $_SESSION["s_workouttime"]; echo '"/></td><td></td>';
	echo '<td><input type="text" name="b_workouttime" id="b_workouttime" TABINDEX=11';
	echo ' value="'; echo $_SESSION["b_workouttime"]; echo '"/></td><td></t>';
	echo '<td><input type="text" name="r_workouttime" id="r_workouttime" TABINDEX=21';
	echo ' value="'; echo $_SESSION["r_workouttime"]; echo '"/></td></tr>';


	echo '<tr><td><label for="duration">Total Time <span class="small">(hh:mm:ss)</span>:</label></td>';
	echo '<td><input type="text" name="s_duration" id="s_duration" TABINDEX=3';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["s_duration"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="b_duration" id="b_duration" TABINDEX=12';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_duration"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="r_duration" id="r_duration" TABINDEX=22';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["r_duration"]; echo '"/></td></tr>'; }
	else
		echo '/></td><td></td></tr>';

	echo '<tr><td><label for="distance">Distance:</label></td>';
	echo '<td><input type="text" name="s_distance" id="s_distance" TABINDEX=4';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["s_distance"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="b_distance" id="b_distance" TABINDEX=13';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_distance"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="r_distance" id="r_distance" TABINDEX=23';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["r_distance"]; echo '"/></td></tr>'; }
	else
		echo '/></td><td></td></tr>';

	echo '<tr><td><label for="notes">Notes: (max 500)</label></td>';
	echo '<td><textarea id="s_notes" name="s_notes" cols="18" rows="10" TABINDEX=5>';
	if( $updating )
	{	echo $_SESSION["s_notes"]; echo '</textarea></td><td></td>'; }
	else
		echo '</textarea></td><td></td>';
	echo '<td><textarea id="b_notes" name="b_notes" cols="18" rows="10" TABINDEX=14>';
	if( $updating )
	{	echo $_SESSION["b_notes"]; echo '</textarea></td><td></td>'; }
	else
		echo '</textarea></td><td></td>';
	echo '<td><textarea id="r_notes" name="r_notes" cols="18" rows="10" TABINDEX=25>';
	if( $updating )
	{	echo $_SESSION["r_notes"]; echo '</textarea></td></tr>'; }
	else
		echo '</textarea></td></tr>';

	echo '<tr><td><label for="avg_rpms">Avg RPMS:</label></td>';
	echo '<td></td><td></td><td><input type="text" name="avg_rpms" id="avg_rpms" TABINDEX=15';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_avgrpms"]; echo '"/></td></tr>'; }
	else
		echo '/></td></tr>';

 	echo '<tr><td><label for="min_hr">Min Hr:</label></td>';
	echo '<td><input type="text" name="s_min_hr" id="s_min_hr" TABINDEX=6';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["s_minhr"];  echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="b_min_hr" id="b_min_hr" TABINDEX=16';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_minhr"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="r_min_hr" id="r_min_hr" TABINDEX=26';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["r_minhr"]; echo '"/></td></tr>'; }
	else
		echo '/></td><td></td></tr>';

	echo '<tr><td><label for="avg_hr">Avg Hr:</label></td>';
	echo '<td><input type="text" name="s_avg_hr" id="s_avg_hr" TABINDEX=7';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["s_avghr"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="b_avg_hr" id="b_avg_hr" TABINDEX=17';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_avghr"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="r_avg_hr" id="r_avg_hr" TABINDEX=27';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["r_avghr"]; echo '"/></td></tr>'; }
	else
		echo '/></td><td></td></tr>';

	echo '<tr><td><label for="max_hr">Max Hr:</label></td>';
	echo '<td><input type="text" name="s_max_hr" id="s_max_hr" TABINDEX=8';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["s_maxhr"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="b_max_hr" id="b_max_hr" TABINDEX=18';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_maxhr"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="r_max_hr" id="r_max_hr" TABINDEX=28';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["r_maxhr"]; echo '"/></td></tr>'; }
	else
		echo '/></td><td></td></tr>';

	echo '<tr><td><label for="calsburned">Calories Burned:</label></td>';
	echo '<td><input type="text" name="s_calsburned" id="s_calsburned" TABINDEX=9';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["s_calsburned"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="b_calsburned" id="b_calsburned" TABINDEX=19';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["b_calsburned"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';
	echo '<td><input type="text" name="r_calsburned" id="r_calsburned" TABINDEX=29';
	if( $updating )
	{	echo ' value="'; echo $_SESSION["r_calsburned"]; echo '"/></td><td></td>'; }
	else
		echo '/></td><td></td>';

	echo '<tr><td><label for="blog_notes">Overall Daily Notes:</label></td>';
	echo '<td colspan=6><textarea id="blog_notes" name="blog_notes" cols="68" rows="10" TABINDEX=30>';
	if( $updating )
	{	echo $_SESSION["blog_notes"]; echo '</textarea></td></tr>'; }
	else
		echo '</textarea></td></tr>';

	echo '</table>';

/*********				Setup the strength training section **************************************/
	echo '<table id="str_workouts">';
	echo '<tr><td colspan=9 align=center><h2>Strength Training</h2></td></tr>';
	echo '<td><select name="category" id="category" onchange="getWorkouts();"/>';
	echo '<option></option>';
	get_strengthcategories();
	echo '</td>';
	echo '<td><select name="descriptions" id="descriptions"/></td>';
	echo '<td>Sets:</td>'; echo '<td width="2"><input type="text" name="sets" id="sets" /></td>';
	echo '<td class="submit" ><input type="button" value="Add Workout" onclick="addRowToTable();" /></td></tr>';
	echo '</table>';

	echo '<table>';
	echo '<tr><td><label for="str_workouttime">Workout time <span class="small">(hh:mm:ss)</span>:</label></td>';
	echo '<td><input type="text" name="str_workouttime" id="str_workouttime" TABINDEX=1';
	echo ' value="'; echo $_SESSION["str_workouttime"]; echo '"/></td></tr>';
	echo '<tr><td><label for="str_duration">Total Time <span class="small">(hh:mm:ss)</span>:</label></td>';
	echo '<td><input type="text" name="str_duration" id="str_duration" TABINDEX=2';
	echo ' value="'; echo $_SESSION["str_duration"]; echo '"/></td></tr>';
	echo '<tr><td><label for="str_notes">Notes: (max 500)</label></td>';
	echo '<td><textarea id="str_notes" name="str_notes" cols="68" rows="10" TABINDEX=3>';
	echo $_SESSION["str_notes"]; echo '</textarea></td></tr>';
	echo '</table>';
/*********				End of the strength training seciont **************************************/
	echo '<input type="hidden" name="updating" id="updating" value="'; echo $updating; echo '"/>';

	echo '<p class="submit"><input type="submit" value="Save Workouts">';

	echo '<input type="reset" value="Reset" /></p>';
	echo '</form>';  		//End of the Form

	// End the session now.
	session_destroy();
}

function create_history()
{
	global $wpdb;
	global $userdata;
	get_currentuserinfo();
	$yearmoday = explode( "-", $_SESSION["nav_month"] );

	// See if the users is trying to display a month other than the current month.
	if( $yearmoday != NULL )
	{
		$display_mo = new Date();
		$display_mo->setDayMonthYear( $yearmoday[2], $yearmoday[1], $yearmoday[0] );
	}
	else
		$display_mo = new Date();

	$LastDay = $display_mo->getYear() . "-" . $display_mo->getMonth() . "-" . $display_mo->GetDaysInMonth();
	$FirstDay = $display_mo->format( "%Y" ) . "-" . $display_mo->format( "%m" ) . "-" . "1";
	$sbr_type = array( "s", "b", "r" );

	$total_duration = array( 0, 0, 0 );
	$total_distance = array( 0, 0, 0 );
	$total_calories = array( 0, 0, 0 );

	for( $i=0; $i<3; $i++)
	{
		$query = "SELECT * FROM " . $wpdb->prefix . "flmain WHERE user_id=" . $userdata->ID . " AND workout_date>='" . $FirstDay . "' AND workout_date<='" . $LastDay . "' AND sbr_type='" . $sbr_type[$i] . "' ORDER BY workout_date ASC";

		$result = $wpdb->get_results( $query, ARRAY_A );

		if( $result )
		{
			switch( $i )
			{
				case 0:
					echo '<h2>Swimming</h2>';
					break;
				case 1:
					echo '<h2>Biking</h2>';
					break;
				case 2:
					echo '<h2>Running</h2>';
					break;
			}

			echo '<table class="history">';
			echo '<tr><th width=80>Date</th><th>Duration</th><th>Distance</th>';
			echo '<th>Calories Burned</th><th>Avg RPMs</th><th>Pace</th><th>Time of Day</th>';
			echo '<th>Min HR</th><th>Avg HR</th><th>Max HR</th><th>Notes</th></tr>';

			foreach( $result as $row )
			{
				echo '<tr>';
				echo '<td><a href="/wp-content/plugins/fitnesslog/fitlogupdate.php?edit_date='; echo $row["workout_date"]; echo '">'; echo date( "M d, Y" , format_date( 	$row["workout_date"] ) ); echo'</a></td>';
				echo '<td>'; echo $row["duration"]; echo '</td>';
				echo '<td>'; echo $row["distance"]; echo '</td>';
				echo '<td>'; echo $row["cals_burned"]; echo '</td>';
				echo '<td>'; echo $row["avg_rpms"]; echo '</td>';
				echo '<td>'; echo $row["pace"]; echo '</td>';
				echo '<td>'; echo $row["time_of_day"]; echo '</td>';
				echo '<td>'; echo $row["min_hr"]; echo '</td>';
				echo '<td>'; echo $row["avg_hr"]; echo '</td>';
				echo '<td>'; echo $row["max_hr"]; echo '</td>';
				echo '<td>'; echo stripslashes( $row["notes"] ); echo '</td>';
				echo '</tr>';

				//Add up the duration, distance and calories
				$total_duration[$i] = $total_duration[$i] + convert_time_seconds( $row["duration"] );
				$total_distance[$i] = $total_distance[$i] + $row["distance"];
				$total_calories[$i] = $total_calories[$i] + $row["cals_burned"];
			}
		
			// Output the totals for each.
			echo '<tr><td>Totals</td><td>'; echo format_time( $total_duration[$i] ); echo '</td>';
			echo '<td>'; echo $total_distance[$i]; echo '</td>';
			echo '<td>'; echo $total_calories[$i]; echo '</td></tr>';
		}

		echo '</table>';
	}

	// Close the session
	session_destroy();
}

function list_exercises()
{
	global $wpdb;
	global $userdata;
	get_currentuserinfo();

	$query = "SELECT * FROM " . $wpdb->prefix . "flexercise_type WHERE user_id=" . $userdata->ID . " ORDER BY category ASC";

	$result = $wpdb->get_results( $query, ARRAY_A );

	echo '<table class="history">';
	echo '<tr><th>Category</th><th>Description</th><th colspan=2>Modify</th></tr>';
	/*<a href="/wp-content/plugins/fitnesslog/fitlogupdate.php?excercise_id=' . $row["exercise_type_id"] . '">edit</a>*/
	foreach( $result as $row )
	{
		echo '<tr><td>' . $row["category"] . '</td><td>' . $row["excercise"] . '</td><td class="submit"><input type="button" value="edit"/></td><td><a href="/wp-content/plugins/fitnesslog/fitlogdelete.php?source=exercise_type&exercise_type_id=' . $row["exercise_type_id"] . '">delete</a></td></tr>';
	}
	echo '</table>';
}

function get_strengthcategories()
{
	global $wpdb;
	global $userdata;
	get_currentuserinfo();

	$query = "SELECT category FROM " . $wpdb->prefix . "flexercise_type WHERE user_id=" . $userdata->ID . " GROUP BY category ORDER BY category ASC";

	$results = $wpdb->get_results( $query, ARRAY_A );

	foreach( $results as $row )
		echo '<option>' . $row["category"] . '</option>';
}
/***************************************** End Fitness Log Settings code *****************************************************************/
function form_days( $firstday, $lastday )
{
	global $wpdb;
	global $userdata;
	get_currentuserinfo();
	$cur_date = new Date($firstday);
	$cur_row = new Date();
	$last_date = new Date($lastday);

	do
	{
		//Setup the row that holds the Date, edit/update and delete information links.
		$formated_day = $formated_day . '<tr><td><small>' . $cur_date->format("%a, %b %d %Y") . '</small></td><td><small><a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/fitlogdelete.php?del_date=' . $cur_date->format( "%Y-%m-%d" ) . '&source=workouts">Delete</a></small></td><td colspan=2><small><a href="' . get_bloginfo('url') . '/wp-content/plugins/fitnesslog/fitlogupdate.php?edit_date=' . $cur_date->format( "%Y-%m-%d" ) . '">Edit today</a></small></td></tr>';

		// Print out the current day's Swim, Bike, Run data and related sport specific notes.
		$query = "SELECT * FROM " . $wpdb->prefix . "flmain WHERE user_id=" . $userdata->ID . " AND workout_date='" . $cur_date->format( "%Y-%m-%d" ) . "' ORDER BY sbr_type ASC";
		$results = $wpdb->get_results( $query, ARRAY_A );

		if( $results )
		{
			foreach( $results as $row )
			{
				// print out the actual workout data now.
				switch( $row["sbr_type"] )
				{
					case "s":
						$formated_day = $formated_day . '<tr><td>Swim: </td>';
						break;
					case "b":
						$formated_day = $formated_day . '<tr><td>Bike: </td>';
						break;
					case "r":
						$formated_day = $formated_day . '<tr><td>Run: </td>';
						break;
				}

				//Setup the row that holds the workout total time, distance and pace information.
				$formated_day = $formated_day . '<td>' . $row["duration"] . '</td><td>' . $row["distance"] . '</td><td>' . $row["pace"] . '</td></tr>';

				if( strlen( $row["notes"] ) )
				{		
					//Setup the notes section for this workout.
					$formated_day = $formated_day . '<tr><td colspan=2>' . stripslashes( $row["notes"] ) . '</td></tr><tr></tr><tr></tr>';
				}
			}
		}

		// Print out the current day's Strength Training data and related notes.
		$query = "SELECT * FROM " . $wpdb->prefix . "flstrength WHERE user_id=" . $userdata->ID . " AND workout_date='" . $cur_date->format( "%Y-%m-%d" ) . "'";

		$results = $wpdb->get_results( $query, ARRAY_A );

		if( $results )
		{
			foreach( $results as $row )
			{
				// Print out the duration and "label"
				$formated_day = $formated_day . '<tr><td>Strength: </td><td>' . $row["duration"] . '</td></tr>';

				// Print out the notes for the current day's strength training.
				$formated_day = $formated_day . '<tr><td colspan=4>' . stripslashes( $row["notes"] ) . '</td></tr><tr></tr><tr></tr>';
			}
		}

		// Print out the overall daily notes now.
		$blog_notes = get_daily_blognotes( $cur_date->format( "%Y-%m-%d" ), $userdata->ID );
		if( $blog_notes )
		{
			$formated_day = $formated_day . '<tr><td>Daily Notes:</td></tr>';
			$formated_day = $formated_day . '<tr><td colspan=4>' . stripslashes( $blog_notes ) . '</td></tr><tr></tr><tr></tr>';
		}

		//Move to the next day to be printed.
		$cur_date->addDays(-1);
	}
	while($cur_date >= $last_date );

	return $formated_day;
}

function get_daily_blognotes( $date, $user_id )
{
	global $wpdb;

	$query_notes = "SELECT notes FROM " . $wpdb->prefix . "flblog WHERE blog_date='" . $date . "' AND user_id=" . $user_id;
	return $wpdb->get_var( $query_notes );

}

function get_summary_totals( $activity, $term )
{
	$beg_date = new Date();
	$end_date = new Date();
	global $wpdb;
	global $userdata;
	get_currentuserinfo();

	// First figure out what term we are looking for.
	// "w" for Weekly
	// "m" for Monthly
	// "A" for Annual
	switch ( $term )
	{
		case "w":
			//Get weekly dates
			$day_of_wk = $beg_date->getDayOfWeek();
			$beg_date->addDays(-($day_of_wk-1));
			$end_date->copy( $beg_date );
			$end_date->addDays( 6 );
			break;
		case "m":
			//Get monthly dates
			$beg_date->setDayMonthYear( 1, $beg_date->getMonth(), $beg_date->getYear() );
			$end_date->copy( $beg_date );
			$end_date->addDays( ( $end_date->getDaysInMonth() - 1 ) );
			break;
		case "a":
			//Get the annual dates.
			$beg_date->setDayMonthYear( 1, 1, $beg_date->getYear() );
			$end_date->setDayMonthYear( 31, 12, $end_date->getYear() );
			break;
		case "pm":
			//Get the prior months date.
			$beg_date->setDayMonthYear( 1, $beg_date->getMonth(), $beg_date->getYear() );
			$end_date = $beg_date->getPrevDay();
			$beg_date->setDayMonthYear( 1, $end_date->getMonth(), $end_date->getYear() );
			break;
		case "ppm":
			//Get the dates for two months from now.
			$beg_date->setDayMonthYear( 1, $beg_date->getMonth(), $beg_date->getYear() );
			$end_date = $beg_date->getPrevDay();
			$beg_date->setDayMonthYear( 1, $end_date->getMonth(), $end_date->getYear() );
			$end_date = $beg_date->getPrevDay();
			$beg_date->setDayMonthYear( 1, $end_date->getMonth(), $end_date->getYear() );
			break;
		case "pppm":
			//Get the dates for three months from now.
			$beg_date->setDayMonthYear( 1, $beg_date->getMonth(), $beg_date->getYear() );
			$end_date = $beg_date->getPrevDay();
			$beg_date->setDayMonthYear( 1, $end_date->getMonth(), $end_date->getYear() );
			$end_date = $beg_date->getPrevDay();
			$beg_date->setDayMonthYear( 1, $end_date->getMonth(), $end_date->getYear() );
			$end_date = $beg_date->getPrevDay();
			$beg_date->setDayMonthYear( 1, $end_date->getMonth(), $end_date->getYear() );
			break;
		case "py":
			//Get the dates for the prior year.
			$beg_date->setDayMonthYear( 1, 1, ( $beg_date->getYear() - 1 ) );
			$end_date->setDayMonthYear( 31, 12, $beg_date->getYear() );
			break;
	}

	switch ( $activity )
	{
		case "s":
			// Get the summary of swim duration.
			$query = "SELECT user_id, SUM(seconds) AS seconds, SUM(distance) AS distance FROM " . $wpdb->prefix . "flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND sbr_type='" . $activity . "' AND user_id=" . $userdata->ID . " GROUP BY user_id";
			break;
		case "b":
			// Get the summary of bike duration.
			$query = "SELECT user_id, SUM(seconds) AS seconds, SUM(distance) AS distance FROM " . $wpdb->prefix . "flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND sbr_type='" . $activity . "' AND user_id=" . $userdata->ID . " GROUP BY user_id";
			break;
		case "r":
			// Get the summary of run duration.
			$query = "SELECT user_id, SUM(seconds) AS seconds, SUM(distance) AS distance FROM " . $wpdb->prefix . "flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND sbr_type='" . $activity . "' AND user_id=" . $userdata->ID . " GROUP BY user_id";
			break;	
		case "str":
			// Get the summary of strength duration.
			$query = "SELECT user_id, SUM(seconds) AS seconds FROM " . $wpdb->prefix . "flstrength WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND user_id=" . $userdata->ID . " GROUP BY user_id";
			break;	
	}

	$result = $wpdb->get_results( $query, ARRAY_A );

	if( $result )
	{
		foreach( $result AS $row )
		{
			// Format the string for out put now.
			$time = format_time( $row["seconds"] );
			$time_array = explode( ":", $time );
			if( $activity == "s" )
				$distance = "yds";
			else
				$distance = "miles";

			$output = $time_array[0] . "hrs " . $time_array[1] . "mins " . $time_array[2] . "secs      " . $row["distance"] . $distance;
		}
	}
	else
		$output = 0;

	return $output;
}

function get_weekly_volume_graph()
{
	global $wpdb;
	global $userdata;
	get_currentuserinfo();

	$beg_date = new Date();
	$end_date = new Date();
	$wk = array( 0, 0, 0, 0 );
	$label = array( 0, 0, 0, 0);
	$filename = array( "filename"=>"/var/www/vanhlebarsoftware/wp-content/plugins/fitnesslog/graphs/wklygraph.png");

	// Get current weeks and prior three weeks volume numbers.
	$day_of_wk = $beg_date->getDayOfWeek();
	$beg_date->addDays(-($day_of_wk - 1));
	$end_date->copy( $beg_date );
	$end_date->addDays( 6 );

	for( $i=0; $i<4; $i++ )
	{
		$query = "SELECT user_id, SUM(seconds) AS seconds FROM " . $wpdb->prefix . "flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND user_id=" . $userdata->ID . " GROUP BY user_id";

		$result = $wpdb->get_results( $query, ARRAY_A );

		if( $result )
		{
			foreach( $result AS $row )
				$wk[$i] = convert_seconds_minutes( $row["seconds"] );
		}
		else
			$wk[$i] = 0;	

		// Add any strength training that we have done to the total.
		$query = "SELECT user_id, SUM(seconds) AS seconds FROM " . $wpdb->prefix . "flstrength WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND user_id=" . $userdata->ID . " GROUP BY user_id";

		$result = $wpdb->get_results( $query, ARRAY_A );

		if( $result )
		{
			foreach( $result AS $row )
				$wk[$i] = $wk[$i] + convert_seconds_minutes( $row["seconds"] );
		}

		// Create the labels.
		$label[$i] = $end_date->format( "%m/%d" );

		// Move the dates back by one week.
		$beg_date->addDays( -7 );
		$end_date->addDays( -7 );
	}

	//Setup the graph.
	$Graph =& Image_Graph::factory('graph', array(175, 175), true);
	$Plotarea =& $Graph->addNew('plotarea');
	$Dataset =& Image_Graph::factory('dataset');
	$Dataset->addPoint( $label[3], $wk[3]);
	$Dataset->addPoint( $label[2], $wk[2]);
	$Dataset->addPoint( $label[1], $wk[1]);
	$Dataset->addPoint( $label[0], $wk[0]);
	$Plot =& $Plotarea->addNew('bar', &$Dataset);
	$Plot->setFillColor( 'green' );
	$YAxis = & $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
	$YAxis->setTitle( 'Minutes', 'vertical' );
	$XAxis = & $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
//	$XAxis->setFontAngle( "vertical" );
	$XAxis->setTitle( "Week", array('angle'=>0) );
	
	//Output the finished graph to the graphs directory.
	$result = $Graph->done( $filename );

	if( $result )
		var_dump( "error creating graph!" ); 
}

function tmp()
{
	global $wpdb;

	$query = "SELECT * FROM wp_flmain";
	$results = $wpdb->get_results( $query, ARRAY_A);

	foreach( $results AS $row )
	{
		$update = "UPDATE wp_flmain SET seconds=" . convert_time_seconds( $row["duration"] ) . " WHERE user_id=" . $row["user_id"] . " AND sbr_type='" . $row["sbr_type"] . "' AND plan_type='" . $row["plan_type"] . "' AND workout_date='" . $row["workout_date"] . "'";

		$wpdb->query($update);
	}
}
?>
