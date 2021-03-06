<?php
/* File: 	fitlogupload.php
   Desciption:	Implementation file for the actual submitting and inserting of workouts via a cvs text file.
   Author:	Eric A. Bonney
   Date:	February 23, 2009
   Updated:	December 2, 2009 - removed the Wordpress items and reworked it to be a stand alone script.
*/
require_once "includes/db.inc";
require_once "fitlogfunc.php";

$plantype = $_POST["plan_type"];

// Setup the defines for the columns that we will be importing.
define( "WORKOUT_DATE", 0 );
define( "SBR_TYPE", 1 );
define( "SECONDS", 2 );
define( "DISTANCE", 3 );
define( "MIN_HR", 4 );
define( "AVG_HR", 5 );
define( "MAX_HR", 6 );
define( "NOTES", 7 );

// Get a connection to the database.
if( !($connection = @mysql_connect( $hostName, $username, $password ) ) )
	die( "Could not connect to database" );

// Now that we are connected, select the correct database.
if( !mysql_select_db( $databaseName, $connection ) )
	showerror();

session_start();

//See if we have an authenticated user, if so, setup the appropriate message.
if( isset( $_SESSION["loggedinUserName"] ) )
{
	$errors = 0;
	$imported = 0;
	$userID = getUserID( $connection );

	switch($plantype)
	{
		case "Planned":
			$plan_type = "p";
			break;
		case "Actual":
			$plan_type = "a";
			break;
	}

	// Where the file is going to be placed , hard coded right now, but will be put into options eventually.
	$target_path = "/var/www/vanhlebarsoftware/fitlog/uploads/";

	/* Set up the allowed file types that can be uploaded. Currently on CSV files will be allowed. */
	$allowedExtensions = array("csv");

	/* Add the original filename to our target path.  
	Result is "/var/www/vanhlebarsoftware/fitlog/uploads/filename.extension" */
	$file_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

	// Get the filename the user attempted to upload.
	$file = $_FILES['uploadedfile']['name'];

	// Move the file to the uploads area
	if( move_uploaded_file( $_FILES['uploadedfile']['tmp_name'], $file_path ) )
	{
		//Change the permissions on the tmp file
		if (!chmod( $file_path, 0755 ))
		{
			$_SESSION["upload_status"] = "Failed to chmod the tmp file!";
		}	
		elseif( isAllowedExtension( $file ) )
		{
			// Read in the entire file and then explode it into the records. Then explode each record and insert that record into the database.
			$fh = fopen( $file_path, "r" );

			// When the record has been exploded the resulting array elements are as follows:
			// [0] = workout_date
			// [1] = sbr_type
			// [2] = seconds
			// [3] = distance
			// [4] = min_hr
			// [5] = avg_hr
			// [6] = max_hr
			// [7] = notes
				
			while( ( $row = fgetcsv( $fh, $delimiter = ';' ) ) !== FALSE )
			{
                            //var_dump($row);
				// formate the duration for now in hh:mm:ss
				$duration = format_time( intval( $row[SECONDS] ) );

				// Convert all the strings into the appropriate int or float value.
				$seconds = intval( $row[SECONDS] );
				$distance = floatval( $row[DISTANCE] );
				$min_hr = intval( $row[MIN_HR] );
				$avg_hr = intval( $row[AVG_HR] );
				$max_hr = intval( $row[MAX_HR] );

				// Get the string values from the array.
				$workout_date = $row[WORKOUT_DATE];
				$sbr_type = $row[SBR_TYPE];
				$notes = $row[NOTES];

				// Calculate the pace inforamtion.
				switch ($row[SBR_TYPE])
				{
					case "s":
						$pace = swimpace( $seconds, $distance );
						break;
					case "b":
						$pace = bikepace( $seconds, $distance );
						break;
					case "r":
						$pace = runpace( $seconds, $distance );
						break;
					default:
						$pace = 0;
						break;
				}

				$query = "INSERT INTO flmain (plan_type, sbr_type, duration, seconds, distance, pace, workout_date, time_of_day, min_hr, avg_hr, max_hr, notes, user_id, cals_burned) VALUES ('$plan_type', '$sbr_type', '$duration', $seconds, $distance, $pace, '$workout_date', '00:00:00', $min_hr, $avg_hr, $max_hr, '$notes', $userID, 0)";
var_dump($query);
				// Run the query
				$result = @ mysql_query( $query, $connection );

				// Need a better failure handler here!
				if( !$result )
				{
					$errors++;
				}
				else
					$imported++;
				$rowsRead++;
			}

			$_SESSION["upload_status"] = $_SESSION["upload_status"] . '<p>Your file was sucessufully uploaded!</p>' . '<p>Total Rows imported: ' . $imported . '</p>' . '<p>Total Errors: ' . $errors . '</p>';
			unlink( $file_path );
		}
		else
		{
			$_SESSION["upload_status"] = '<p>Invalid file type!</p><p>At this time only CSV file types are allowed to be uploaded. Please try again.</p>';
		}
	}
	else
	{
		$_SESSION["upload_status"] = '<p>Your file was NOT uploaded. An error occurred!</p>';
	}

	// Send the user back to the Training log page.
	header( "Location: trainingplans.php" );
	exit;
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
/******************************************** Begin Helper Functions *******************************************/
function isAllowedExtension($fileName)
{
  global $allowedExtensions;

  return in_array(end(explode(".", $fileName)), $allowedExtensions);
}
?>