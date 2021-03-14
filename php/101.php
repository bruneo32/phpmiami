<?php
  /*
   * 101.php
   * Used by AJAX JavaScript to execute an SQL query and return its result.
   */

  error_reporting(E_ALL ^ E_WARNING); // Supress warnings, comment to debug.
  session_start();
  require("conn.php");

  $conn=connect();
  if(!$conn){die("<b>[System]</b> Conection failed - ".mysqli_connect_errno()."<br>".mysqli_connect_error());}

  $query = $_REQUEST["q"];
  $fin = array(array());
  $j=0;


  if (mysqli_multi_query($conn,$query)) {
    $result = mysqli_store_result($conn);
    if (mysqli_errno($conn) == 0) {

      /* First result set or FALSE (if the query didn't return a result set) is stored in $result */
      if(gettype($result)=="object"){
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($fin[$j],$row);
        }
      }else{
        $fin[$j]=$result;
      }


      while (mysqli_more_results($conn)) {
        $j++;
        array_push($fin,array());

        if (mysqli_next_result($conn)) {
          $result = mysqli_store_result($conn);
          if (mysqli_errno($conn) == 0) {
            /* The result set or FALSE (see above) is stored in $result */
            if(gettype($result)=="object"){
              while ($row = mysqli_fetch_assoc($result)) {
                array_push($fin[$j],$row);
              }
            }else{
              $fin[$j]=$result;
            }
          } else {
            /* Result set read error */
            break;
          }
        } else {
          /* Error in the query */
          array_pop($fin);
          array_push($fin,"<b>[ERROR]</b> - ".mysqli_errno($conn)."<br>".mysqli_error($conn));
        }
      }
    } else {
      /* First result set read error */
    }
  } else {
    die("<b>[ERROR]</b> - ".mysqli_errno($conn)."<br>".mysqli_error($conn));
  }

  echo json_encode($fin);
?>
