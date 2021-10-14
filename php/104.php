<?php
/*
* 104.php
* Import a database
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

$filename = $_POST["f_imp_file"];
$filestr  = $_POST["f_imp_filestr"];
$ftype    = $_POST["f_imp_format"];


// ECHO HTML HEADER
echo '<!DOCTYPE html><html lang="en" dir="ltr"><head><meta charset="utf-8"><title>Import/Export · PHP Miami</title><link rel="shortcut icon" type="image/x-icon" href="../res/icon.jpg"><link rel="stylesheet" href="../style.css"><link rel="stylesheet" href="#" id="externalStylesheet"><script src="../script.js" charset="utf-8"></script><script src="../theme.js" charset="utf-8"></script><style>.result_query{display: table-row;}</style></head><body onload="LoadStyles();ScrollBottom()">';
echo '<div id="topbar"><img src="../res/banner.jpg" alt=""><a href="../main">Return to PHPMIAMI</a><div style="float:right;height: 100%;padding: 0.25em 0;"><span>'. $_SESSION["u"]."@".$_SESSION["hn"].'</span><a href="102.php">Log out</a></div></div><div id="main">';

if($filestr == ""){
	die("The file is empty</div></body></html>");
}
echo "<b>Filename:</b>&ensp;".$filename."<br/>";
echo "<b>Filetype:</b>&ensp;&ensp;".$ftype."<br/>";

$stateok=true;

if($ftype=="sql"){

	// QUERY
	$filestr=html_entity_decode($filestr, ENT_QUOTES | ENT_HTML5);

	$result=mysqli_multi_query($conn, $filestr);
	if(!$result){$stateok=false;}

	while (mysqli_more_results($conn)) {
		if (!mysqli_next_result($conn)) {
			$stateok=false;
			break;
		}
	}

	// echo
	echo '<div class="card" '.($stateok ? 'style="background:var(--color2);"' : '').'><table><tr><td>SQL</td><td>';
	echo '<div class="textin">'.$filestr.'</div>';
	echo '</td></tr><tr class="result_query"><td>'.($stateok ? "Affected Rows" : "Error").'</td><td><p style="font-family: \'Roboto\', monospace;">'.($stateok ? mysqli_affected_rows($conn) : "[".mysqli_errno($conn)."] ".mysqli_error($conn)).'</p></td></tr></table></div>';

	if($stateok && !$thisok){$stateok=false;}
}else{
	if(file_exists("import/".$ftype.".php")){

		require_once("import/".$ftype.".php");

		$x=IMPORT($conn,$filestr);
		if($x===false){
			$stateok=false;
			$x=		"MYSQLERROR:\n".mysqli_error($conn) .endl;
		}

		$x=html_entity_decode($x, ENT_QUOTES | ENT_HTML5);
		$result=mysqli_multi_query($conn, $x);
		if(!$result){$stateok=false;}

		while (mysqli_more_results($conn)) {
			if (!mysqli_next_result($conn)) {
				$stateok=false;
				break;
			}
		}

		// echo
		echo '<div class="card" style="background:var(--color2);"><table><tr><td>INPUT</td><td>';
		echo '<div class="textin">'.$filestr.'</div>';
		echo '</td></tr></table></div>';

		echo '<div class="card" '.($stateok ? 'style="background:var(--color2);"' : '').'><table><tr><td>SQL</td><td>';
		echo '<div class="textin">'.$x.'</div>';
		echo '</td></tr><tr class="result_query"><td>'.($stateok ? "Affected Rows" : "Error").'</td><td><p style="font-family: \'Roboto\', monospace;">'.($stateok ? mysqli_affected_rows($conn) : "[".mysqli_errno($conn)."] ".mysqli_error($conn)).'</p></td></tr></table></div>';
	}else{
		mysqli_close($conn);
		die("<br/><p>Unknown format ".$ftype."</p></div></body></html>");
	}
}
mysqli_close($conn);

echo "<br/>";
if($stateok){
	echo "<p>Import successful!</p>";
}else{
	echo "<p>An error occurred importing <b>".$filename."</b></p>";
	echo "<br/>";
}

// ECHO HTML TAIL
echo "</div></body></html>";

?>
