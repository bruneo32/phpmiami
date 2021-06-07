<?php

function IMPORT($conn, $filestr){
	if(!isset($conn)){return false;}
	if(!isset($filestr)){return false;}

	libxml_use_internal_errors(true);
	$xml=simplexml_load_string(str_replace("\n","",html_entity_decode($filestr)));
	if(!$xml){
		$e="XML ERRORS:" .endl;
		foreach(libxml_get_errors() as $error) {
			$e.= " [Line ".$error->line."] ". $error->message .endl;
		}
		return $e;
	}

	$db=$xml->database[0];
	$dbn=$db["name"];

	$x =	"/*\n * XML TO SQL FROM PHPMIAMI\n */" .endl.endl;
	$x.=	'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'.endl.'SET time_zone = "+00:00";' .endl.endl;
	$x.=	'/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;'.endl.'/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;'.endl.'/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;'.endl.'/*!40101 SET NAMES utf8 */;' .endl.endl.endl;

	$x.=	"DROP DATABASE IF EXISTS `$dbn`;" .endl;
	$x.=	"CREATE DATABASE `$dbn` /*!40100 DEFAULT CHARACTER SET utf8 */;" .endl;
	$x.=	"USE `$dbn`;" .endl.endl.endl;

	// COUNT TABLES
	$tabs=0;$views=0;$procs=0;
	foreach ($db->children() as $key => $value) {
		if ($key=="table"){
			$x.=	'DROP TABLE IF EXISTS `'.$value["name"].'`;' .endl;
			$x.=	trim($value->create).";".endl;
			$x.=	'INSERT INTO `'.$value["name"].'` VALUES'.endl;

			$tab_data=$value->data;
			$j=count($tab_data->children())-1;
			foreach ($tab_data->children() as $key => $dr) {
				$x.=	"(";

				$k=count($dr->children())-1;
				foreach ($dr->children() as $key => $dd) {
					$x.= "\"".$dd."\"" .($k>0 ? ", " : "");
					$k--;
				}

				$x.=	")";
				$x.=	($j>0 ? ",":";") .endl;
				$j--;
			}

			$x.=	endl;
		}else if($key=="view"){
			$x.=	'DROP VIEW IF EXISTS `'.$value["name"].'`;' .endl;
			$x.=	trim($value).";".endl;
			$x.=	endl;
		}else if($key=="procedure"){
			$x.=	'DROP PROCEDURE IF EXISTS `'.$value["name"].'`;' .endl;
			$x.=	trim($value).";".endl;
			$x.=	endl;
		}
	}

	$x.=	"/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
	return $x;
}

?>
