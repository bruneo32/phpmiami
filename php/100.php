<?php
  /*
   * 100.php
   * Login
   */

  $LOCALHOST=(($_SERVER["SERVER_ADDR"]=="::1" || $_SERVER["SERVER_ADDR"]=="127.0.0.1") ? true:false); // Turn true to allow localhost connections.
  //$LOCALHOST=true; // Enable this line and comment the previous one to allow localhost access.

  error_reporting(E_ALL ^ E_WARNING); // Supress warnings, comment to debug.
  session_start();
  require("conn.php");

  $h=$_REQUEST["h"];
  $hn=$h;

  if(!$LOCALHOST){
    if($h == "localhost"){die("For security reasons, conections to 'localhost' are bloqued. Learn more about localhost <a href=\"\">here</a>.<br><br><a href='../login'>Return</a>");}
  }
  $u=$_REQUEST["u"];

  $_SESSION["h"]=$h;
  $_SESSION["hn"]=$hn;
  $_SESSION["u"]=$u;
  $_SESSION["p"]=$_REQUEST["p"];
  $_SESSION["db"]=$_GET["db"];
  $_SESSION["po"]=$_GET["po"];
  $_SESSION["so"]=$_GET["so"];

  if(!connect()){
    die("<b>[System]</b> Conection failed - ".mysqli_connect_errno()."<br>".mysqli_connect_error()."<br><br><a href='../login'>Return</a>");
  }

  //header('Location: ../main');
  die('<meta http-equiv="refresh" content="0; URL=../main">');
?>
