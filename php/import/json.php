<?php

function IMPORT($conn, $filestr){
	if(!isset($conn)){return false;}
	if(!isset($filestr)){return false;}

	$text=str_replace("\r","",str_replace("\n","",str_replace("\t","",html_entity_decode($filestr))));
	$fjson = json_decode($text, true);

	if($fjson == null){
		return "JSON ERROR: ".json_last_error_msg().endl.endl.$text;
	}else{
		$_t=array_column($fjson,"type");
		$db_i = array_search("database", $_t);

		$dbn = $fjson[$db_i]["name"];

		$x =	"/*\n * JSON TO SQL FROM PHPMIAMI\n */" .endl.endl;
		$x.=	'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'.endl.'SET time_zone = "+00:00";' .endl.endl;
		$x.=	'/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;'.endl.'/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;'.endl.'/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;'.endl.'/*!40101 SET NAMES utf8 */;' .endl.endl.endl;

		$x.=	"DROP DATABASE IF EXISTS `$dbn`;" .endl;
		$x.=	"CREATE DATABASE `$dbn` /*!40100 DEFAULT CHARACTER SET utf8 */;" .endl;
		$x.=	"USE `$dbn`;" .endl.endl.endl;

		// COUNT TABLES
		$tabs=0;$views=0;$procs=0;
		foreach ($_t as $key => $v) {
			if ($v=="table") $tabs++;
			if ($v=="view") $views++;
			if ($v=="procedure") $procs++;
		}

		// FOR TABLES
		for ($i=0; $i < $tabs; $i++) {
			$db_t = array_search("table", array_column($fjson,"type"));
			$tab_name=$fjson[$db_t]["name"];
			$tab_data=$fjson[$db_t]["data"];

			$tab_create=$fjson[$db_t]["create"];


			$x.=	'DROP TABLE IF EXISTS `'.$tab_name.'`;' .endl;
			$x.=	$tab_create.";".endl;

			$x.=	"INSERT INTO `".$tab_name."` VALUES\n";
			for ($j=0; $j < count($tab_data); $j++) {
				$x.=	"(";
				$zzz = $tab_data[$j];

				$k=0;
				foreach ($zzz as $key => $value) {
					$x.=	"\"".$value . "\"" .($k!=count($zzz)-1 ? ", " : "");
					$k++;
				}
				$x.=	")";
				$x.=	($j!=count($tab_data)-1 ? ",":";") .endl;
			}

			$x.= endl.endl;
			array_splice($fjson, $db_t, 1);
		}
	}

	// VIEWS
	for ($i=0; $i < $views; $i++) {
		$db_t = array_search("view", array_column($fjson,"type"));
		$view_name=$fjson[$db_t]["name"];

		$view_create=$fjson[$db_t]["create"];

		$x.=	'DROP VIEW IF EXISTS `'.$view_name.'`;' .endl;
		$x.=	$view_create.";".endl;

		$x.= endl.endl;
		array_splice($fjson, $db_t, 1);
	}

	// PROCEDURES
	for ($i=0; $i < $views; $i++) {
		$db_t = array_search("procedure", array_column($fjson,"type"));
		$proc_name=$fjson[$db_t]["name"];

		$proc_create=$fjson[$db_t]["create"];

		$x.=	'DROP PROCEDURE IF EXISTS `'.$proc_name.'`;' .endl;
		$x.=	$proc_create.";".endl;

		$x.= endl.endl;
		array_splice($fjson, $db_t, 1);
	}

	$x.=	"/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";

	return $x;
}

?>
