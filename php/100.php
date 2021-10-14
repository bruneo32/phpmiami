<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PHP Miami</title>
    <link rel="shortcut icon" type="image/x-icon" href="../res/icon.jpg">
    <link rel="stylesheet" href="../style.css">
	<link rel="stylesheet" href="#" id="externalStylesheet">

    <style>
		body{padding:32px;}
    </style>
	
	<script src="../theme.js" charset="utf-8"></script>
  </head>
  <body onload="LoadStyles()">
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
    if($h=="localhost" || $h=="127.0.0.1" || $h=="::1"){die("For security reasons, conections to 'localhost' are bloqued. Learn more about localhost <a href=\"../doc/localhost.html\">here</a>.<br><br><a href='../login'>Return</a>");}
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
    die("<b>[System]</b> Conection failed - ".mysqli_connect_errno()."<br>".mysqli_connect_error()."<br><br><a class='button' href='../login'>Return</a>".
	"</body></html>");
  }

  //header('Location: ../main'); // This won't work on some servers
  die('<meta http-equiv="refresh" content="0; URL=../main">');
?>

  </body>
</html>
