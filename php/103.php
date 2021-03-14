<?php
  /*
   * 103.php
   * Returns the hintOptions[table] for the code editor using AJAX.
   */

  error_reporting(E_ALL ^ E_WARNING); // Supress warnings, comment to debug.
  session_start();
  require("conn.php");

  $conn=connect();
  $array=array();

  $result=mysqli_query($conn,"SHOW TABLES");
  if(!$result){die();}

  $tables=mysqli_fetch_all($result);
  for ($i=0; $i < count($tables); $i++) {
    $a=strval($tables[$i][0]);
    $array[$a]=array();

    $result=mysqli_query($conn,"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$a."'");
    if(!$result){die();}

    $cols=mysqli_fetch_all($result);
    for ($j=0; $j < count($cols); $j++) {
      $b=strval($cols[$j][0]);
      array_push($array[$a],$b);
      if(!isset($array[$b])){$array[$b]=array();}
    }
  }


  echo json_encode($array);
?>
