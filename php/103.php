<?php
  /*
   * 103.php
   * Returns the hintOptions[table] for the code editor using AJAX.
   */


  require("config.php");
  if(!$DEBUG_MODE){error_reporting(E_ALL ^ E_WARNING);} // Supress warnings
  session_start();
  require("conn.php");

  $conn=connect();
  $array=array();

  if($_SESSION["db"]==""){
    // HINT DATABASES
    $q="SHOW DATABASES";
    $q2="SHOW TABLES FROM (a)";
  }else{
    // HINT TABLES
    $q="SHOW TABLES";
    $q2="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'(a)'";
  }

  $result=mysqli_query($conn,$q);
  if(!$result){die();}

  $tables=mysqli_fetch_all($result);
  for ($i=0; $i < count($tables); $i++) {
    $a=strval($tables[$i][0]);
    $array[$a]=array();

    $result=mysqli_query($conn,str_replace("(a)",$a,$q2));
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
