<?php
/* File: 	reports.php
   Desciption:	Implementation file for the user's reports.
   Author:	Eric A. Bonney
   Date:	December 10, 2009
   Updated:	
*/
require_once "includes/db.inc";
require_once "HTML/Template/IT.php";
require_once "Date.php";
require_once 'Image/Graph.php';
require_once "fitlogfunc.php";

$reportRequested = $_GET["reportRequested"];

// Create the template and load the correct template file.
$template = new HTML_Template_IT( "./templates" );
$template->loadTemplatefile( "reports.tpl", true, true );

session_start();

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

$userID = getUserID( $connection );

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	$date = new Date();

	// Populate the navigation area.
	$template->setCurrentBlock( "NAVIGATION" );
	$template->setVariable( "DATE", $date->format( "%A %b %e, %Y" ) );
	setSelectOption( $reportRequested, $template );
	$template->parseCurrentBlock();

	// Generate the requested report
	switch( $reportRequested )
	{
		case "VolbyYr":
			loadAnnualVolbyType( $userID, $date, $template );
			$template->setCurrentBlock( "REPORTAREA" );
			$template->setVariable( "FILENAME", "annualvolgraph.jpg" );
			$template->setVariable( "ALTNAME", "Volume by Year" );
			$template->parseCurrentBlock();
			break;
		case "DistbyYr":
			loadAnnualDistbyYear( $userID, $date, $template );
			$template->setCurrentBlock( "REPORTAREA" );
			$template->setVariable( "FILENAME", "annualdistgraph.jpg" );
			$template->setVariable( "ALTNAME", "Distance by Year" );
			$template->parseCurrentBlock();			
			break;
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

//Show the user's Dashboard page.
$template->show();
/*********************************** Helper Functions ****************************************************/
function loadAnnualVolbyType( $userID, $date, $template )
{
	$Volume = array();
	$swim_vol = array();
	$bike_vol = array();
	$run_vol = array();

	$beg_date = new Date();
	$end_date = new Date();
	$filename = array( "filename"=>"/var/www/vanhlebarsoftware/fitlog/graphs/annualvolgraph.jpg");

	// Get the earliest and latest Year for our loop.
	$query = "SELECT workout_date FROM flmain WHERE plan_type='a' GROUP BY workout_date ASC";
	$result = @ mysql_query( $query );

	$first = TRUE;
	while( $row = mysql_fetch_array( $result ) )
	{
		if( $first )
		{	
			$first_year = explode( "-" , $row["workout_date"] );
			$first = FALSE;
		}
		else
			$last_year = explode( "-", $row["workout_date"] );
	}

	// Setup the dates.
	$beg_date->setDayMonthYear( 1, 1, $first_year[0] );
	$end_date->setDayMonthYear( 31, 12, $first_year[0] );

	for( $i = intval( $first_year[0] ); $i <= intval( $last_year[0] ); $i++ )
	{
		// Move on to the next year only if we are not on the first year.
		if( $i != intval( $first_year[0] ) )
		{
			$beg_date->setDayMonthYear( 1, 1, $i );
			$end_date->setDayMonthYear( 31, 12, $i );
		}

		$query = "SELECT sbr_type, plan_type, SUM(seconds) AS seconds FROM flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND plan_type='a'" . " AND user_id=" . $userID . " GROUP BY sbr_type ASC";

		$result = @ mysql_query( $query );

		$bikerow = mysql_fetch_array( $result );
		$runrow = mysql_fetch_array( $result );
		$swimrow = mysql_fetch_array( $result );

		// Fill in the Dataset.
		$swim_vol[$i] = convert_seconds_minutes( $swimrow["seconds"] );
		$bike_vol[$i] = convert_seconds_minutes( $bikerow["seconds"] );
		$run_vol[$i] = convert_seconds_minutes( $runrow["seconds"] );
	}

	// Push the three datasets into the $Volume and $Distance datasets to plot on the graph.
	$Volume["swim"] = $swim_vol;
	$Volume["bike"] = $bike_vol;
	$Volume["run"] = $run_vol;

	//Setup the graph.
	$Graph =& Image_Graph::factory('graph', array(
						array(
						  "width" => 800,
						  "height" => 400 ) ) );
	$Graph->add(
	    Image_Graph::vertical(
	        Image_Graph::factory('title', array('Annual Volume', 12)),        
	        Image_Graph::vertical(
	            $Plotarea = Image_Graph::factory('plotarea'),
	            $Legend = Image_Graph::factory('legend'),
	            90
	        ),
	        5
    		)
	);
	$Legend->setPlotarea($Plotarea); 

	// Add the actual volume for each activity to the graph, side-by-side
	foreach ($Volume as $name => $dataset)
	{
		$data =& Image_Graph::factory('dataset');
		$data->setName($name);
		foreach ($dataset as $x => $y)
		{
			$data->addPoint($x,$y);
		}
		$dataset_vol[] = $data;
	}

	// Plot the data to the graph.
	$Plot =& $Plotarea->addNew( 'bar', array($dataset_vol) );

	// Set the colors for the bars.
	$FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
	$FillArray->addColor('lightblue');
	$FillArray->addColor('yellow');
	$FillArray->addColor('lightgreen');

	// Set the fill style
	$Plot->setFillStyle( $FillArray );

	// Setup the axis titles.
	$YAxis =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
	$YAxis->setTitle( 'Minutes', 'vertical' );
	$XAxis =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
	$XAxis->setTitle( "Year", array('angle'=>0) );
	
	//Output the finished graph to the graphs directory.
	$result = $Graph->done( $filename );

	if( $result )
		var_dump( "error creating graph!" );
}

function loadAnnualDistbyYear( $userID, $date, $template )
{
	$Distance = array();
	$swim_dist = array();
	$bike_dist = array();
	$run_dist = array();

	$beg_date = new Date();
	$end_date = new Date();
	$filename = array( "filename"=>"/var/www/vanhlebarsoftware/fitlog/graphs/annualdistgraph.jpg");

	// Get the earliest and latest Year for our loop.
	$query = "SELECT workout_date FROM flmain WHERE plan_type='a' GROUP BY workout_date ASC";
	$result = @ mysql_query( $query );

	$first = TRUE;
	while( $row = mysql_fetch_array( $result ) )
	{
		if( $first )
		{	
			$first_year = explode( "-" , $row["workout_date"] );
			$first = FALSE;
		}
		else
			$last_year = explode( "-", $row["workout_date"] );
	}

	// Setup the dates.
	$beg_date->setDayMonthYear( 1, 1, $first_year[0] );
	$end_date->setDayMonthYear( 31, 12, $first_year[0] );

	for( $i = intval( $first_year[0] ); $i <= intval( $last_year[0] ); $i++ )
	{
		// Move on to the next year only if we are not on the first year.
		if( $i != intval( $first_year[0] ) )
		{
			$beg_date->setDayMonthYear( 1, 1, $i );
			$end_date->setDayMonthYear( 31, 12, $i );
		}

		$query = "SELECT sbr_type, plan_type, SUM(distance) AS distance FROM flmain WHERE workout_date>='" . $beg_date->format( "%Y-%m-%d" ) . "' AND workout_date<='" . $end_date->format( "%Y-%m-%d" ) . "' AND plan_type='a'" . " AND user_id=" . $userID . " GROUP BY sbr_type ASC";

		$result = @ mysql_query( $query );

		$bikerow = mysql_fetch_array( $result );
		$runrow = mysql_fetch_array( $result );
		$swimrow = mysql_fetch_array( $result );

		// Fill in the Dataset.
		//$swim_dist[$i] = $swimrow["distance"];
		$bike_dist[$i] = $bikerow["distance"];
		$run_dist[$i] = $runrow["distance"];
	}

	// Push the three datasets into the $Volume and $Distance datasets to plot on the graph.
	//$Distance["swim"] = $swim_dist;
	$Distance["bike"] = $bike_dist;
	$Distance["run"] = $run_dist;

	//Setup the graph.
	$Graph =& Image_Graph::factory('graph', array(
						array(
						  "width" => 800,
						  "height" => 400 ) ) );
	$Graph->add(
	    Image_Graph::vertical(
	        Image_Graph::factory('title', array('Annual Volume', 12)),        
	        Image_Graph::vertical(
	            $Plotarea = Image_Graph::factory('plotarea'),
	            $Legend = Image_Graph::factory('legend'),
	            90
	        ),
	        5
    		)
	);
	$Legend->setPlotarea($Plotarea); 

	// Add the actual volume for each activity to the graph, side-by-side
	foreach ($Distance as $name => $dataset)
	{
		$data =& Image_Graph::factory('dataset');
		$data->setName($name);
		foreach ($dataset as $x => $y)
		{
			$data->addPoint($x,$y);
		}
		$dataset_dist[] = $data;
	}

	// Plot the data to the graph.
	$Plot =& $Plotarea->addNew( 'bar', array($dataset_dist) );

	// Set the colors for the bars.
	$FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
	$FillArray->addColor('lightblue');
	$FillArray->addColor('yellow');
	$FillArray->addColor('lightgreen');

	// Set the fill style
	$Plot->setFillStyle( $FillArray );

	// Setup the axis titles.
	$YAxis =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
	$YAxis->setTitle( 'Miles', 'vertical' );
	$XAxis =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
	$XAxis->setTitle( "Year", array('angle'=>0) );
	
	//Output the finished graph to the graphs directory.
	$result = $Graph->done( $filename );

	if( $result )
		var_dump( "error creating graph!" );
}

function setSelectOption( $display, $template )
{
	switch ( $display )
	{
		case "None":
			$template->setVariable( "SELECTEDNONE", 'selected="selected"' );
			break;
		case "VolbyYr":
			$template->setVariable( "SELECTEDVOLBYYR", 'selected="selected"' );
			break;
		case "DistbyYr":
			$template->setVariable( "SELECTEDDISTBYYR", 'selected="selected"' );
			break;
		default:
			$template->setVariable( "SELECTEDNONE", 'selected="selected"' );
			break;
	}
}
?>