<?php
/*
* 105.php
* Export a database
*/

require("config.php");
if(!$DEBUG_MODE){error_reporting(E_ALL ^ E_WARNING);} // Supress warnings
session_start();
require("conn.php");

define("endl","\n");

$conn=connect();
if(!$conn){
	die("<b>[System]</b> Conection failed - ".mysqli_connect_errno()."<br>".mysqli_connect_error()."<br><br><a href='../login'>Return</a>");
}

if(!isset($_SESSION["h"])){header('Location: ../login');}

$database		= $_POST["f_exp_db"];
$incSP			= ($_POST["f_exp_sp"]=='on');
$tablename	= $_POST["f_exp_table"];
$filename		= $_POST["f_exp_name"];
$ftype			= $_POST["f_exp_format"];
$fcharset		= $_POST["f_exp_charset"];

if($ftype[0]=="_"){$pftype=substr($ftype,1);}else{$pftype=$ftype;}

// ECHO HTML HEADER
echo '<!DOCTYPE html><html lang="en" dir="ltr"><head><meta charset="utf-8"><title>Import/Export · PHP Miami</title><link rel="shortcut icon" type="image/x-icon" href="../res/icon.jpg"><link rel="stylesheet" href="../style.css"><link rel="stylesheet" href="#" id="externalStylesheet"><script src="../script.js" charset="utf-8"></script><script src="../theme.js" charset="utf-8"></script><style>.result_query{display:initial;}</style></head><body onload="LoadStyles();ScrollBottom()">';
echo '<div id="topbar"><img src="../res/banner.jpg" alt=""><a href="../main">Return to PHPMIAMI</a><div style="float:right;height: 100%;padding: 0.25em 0;"><span>'. $_SESSION["u"]."@".$_SESSION["hn"].'</span><a href="102.php">Log out</a></div></div><div id="main">';

echo "<b>DB:</b>&emsp;".$database."<br/>";
echo "<b>Include procedures:</b>&emsp; ".($incSP ? "true" : "false")." <br/>";
if($tablename!=""){echo "<b>Table:</b>&emsp; $tablename <br/>";}
echo "<b>Filename:</b>&emsp;".$filename."<br/>";
echo "<b>Filetype:</b>&emsp;".$pftype."<br/>";
echo "<b>Charset:</b>&emsp;".$fcharset."<br/>";

$gdt = date(DATE_W3C);
$stateok=true;

if($ftype=="sql"){
	// SQL

	require_once("sql_dumper.php");
	$x="";

	try{
		$x.= "-- SQL EXPORT FROM PHPMIAMI".endl;
		$x.= "-- USING: MySQL Export Database (https://github.com/ttodua/useful-php-scripts)".endl;
		$x.= endl;
		$x.= "/*" .endl;
		$x.= " * Host: 				".$_SESSION["h"] .endl;
		$x.= " * Generation time: 	".$gdt .endl;
		$x.= " * PHP Version: 		".phpversion() .endl;
		$x.= " */" .endl .endl .endl;

		$x.= EXPORT_DATABASE($_SESSION["h"],$_SESSION["u"],$_SESSION["p"],$database, $incSP);
	} catch(Shuttle_Exception $e) {
		$stateok=false;
		$x= "Couldn't dump database: " . $e->getMessage();
	}

	echo '<div class="card"'.($stateok ? 'style="background:var(--color2);"' : '').'><table><tr><td>RAW</td><td>';
	echo '<textarea class="textin" id="vtext" style="background: var(--aux1); color: var(--aux2);">'.$x.'</textarea>';
	echo "<br><button class='button' onclick='DownloadText(\"$filename\",\"vtext\",\"$fcharset\")'>DOWNLOAD</button>";
	echo '<input type="button" style="float:right;" class="button" value="Copy to clipboard" onclick="CopyBtn(this)"/>';
	echo '</td></tr></table></div>';
}else{
	if($tablename!=""){$_te="table/";}else{$_te="";}

	if(file_exists("export/".$_te.$ftype.".php")){
		// PHP
		require_once("export/".$_te.$ftype.".php");

		$x= EXPORT($conn,$database,$gdt,($_te=="" ? $incSP : $tablename));
		if($x===false){
			$stateok=false;
			$x=		"MYSQLERROR:\n".mysqli_error($conn) .endl;
		}

		echo '<div class="card"'.($stateok ? 'style="background:var(--color2);"' : '').'><table><tr><td>RAW</td><td>';
		echo '<textarea class="textin" id="vtext" style="background: var(--aux1); color: var(--aux2);">'.$x.'</textarea>';
		echo "<br><button class='button' onclick='DownloadText(\"$filename\",\"vtext\",\"$fcharset\")'>DOWNLOAD</button>";
		echo '<input type="button" style="float:right;" class="button" value="Copy to clipboard" onclick="CopyBtn(this)"/>';
		echo '</td></tr></table></div>';
	}else{
		mysqli_close($conn);
		die("<br/><p>Unknown format ".$ftype."</p></div></body></html>");
	}
}
mysqli_close($conn);

echo "<br/>";
if($stateok){
	echo "<p>Export successful!</p>";
}else{
	echo "<p>There was an error exporting <b>$database</b> to <b>".$filename."</b></p>";
}
echo "<br/>";

// ECHO HTML TAIL
echo "</div></body></html>";

?>
