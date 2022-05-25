<?php
	/*
	* 106.php
	* Change the database without login using AJAX.
	*/

	require("config.php");
	if(!$DEBUG_MODE){error_reporting(E_ALL ^ E_WARNING);} // Supress warnings
	session_start();
	require("conn.php");

	$pre = session_encode();

	if(isset($_GET["db"])){
		$_SESSION["db"] = $_GET["db"];

		if(connect() != false){
			echo "1";
		}else{
			session_decode($pre);
			echo $_SESSION["db"];
			connect();
		}
	}else{
		session_decode($pre);
		echo $_SESSION["db"];
		connect();
	}

?>
