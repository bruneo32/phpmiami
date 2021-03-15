<?php
  /*
   * 100.php
   * Login
   */


  require("config.php");
  if(!$DEBUG_MODE){error_reporting(E_ALL ^ E_WARNING);} // Supress warnings
  session_start();
  require("conn.php");

  $h=$_REQUEST["h"];
  $hn=$h;

  if(!$_CONFIG["allow_localhost"]){
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

  //header('Location: ../main'); // This won't work on some servers
  die('<meta http-equiv="refresh" content="0; URL=../main">');
?>
