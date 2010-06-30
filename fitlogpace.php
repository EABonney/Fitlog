<?php
     require 'fitlogfunc.php';
     
     // Get time, distance and activity
     $strtime = strval($_GET['time']);
     $distance = floatval($_GET['distance']);
     $activity = strval($_GET['activity']);
     
     // convert the parsed time into seconds and put that into the $time variable
     $time = convert_time_seconds( $strtime );
     
     switch( $activity )
     {
     case "swim":
     $pace = swimpace( $time, $distance );
     break;
     case "bike":
     $pace = bikepace( $time, $distance );
     break;
     case "run":
     $pace = runpace( $time, $distance );
     break;
     default:
     $pace = 0;
     break;
     }
     
     echo ( $pace );
?>
