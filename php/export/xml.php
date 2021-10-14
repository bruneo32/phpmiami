<?php

function EXPORT($conn, $database, $gdt, $incStoredProcedures){
	if(!isset($conn)){return false;}
	if(!isset($database)){return false;}
	if(!isset($gdt)){return false;}
	if(!isset($incStoredProcedures)){return false;}

	$x=		'<?xml version="1.0" encoding="utf-8"?>' .endl.endl;
	$x.=	'<!--' .endl;
	$x.=	'- XML EXPORT FROM PHPMIAMI' .endl;
	$x.=	'-' .endl;
	$x.=	'- Host:				'. $_SESSION["h"] .endl;
	$x.=	'- Generation Time:	'.$gdt .endl;
	$x.=	'- PHP Version:		'. phpversion() .endl;
	$x.=	'-->' .endl.endl;

	$x.=	'<!DOCTYPE phpmiami[' .endl;
	$x.=	'	<!ELEMENT phpmiami (database*)>' .endl;
	$x.=	endl;
	$x.=	'	<!ELEMENT database (table*,view*,procedure*)>' .endl;
	$x.=	'	<!ATTLIST database name CDATA #REQUIRED>' .endl;
	$x.=	endl;
	$x.=	'	<!ELEMENT table (create,data)>' .endl;
	$x.=	'	<!ATTLIST table name CDATA #REQUIRED>' .endl;
	$x.=	'	<!ELEMENT create (#PCDATA)>' .endl;
	$x.=	'	<!ELEMENT data (dr*)>' .endl;
	$x.=	'	<!ELEMENT dr (dd*)>' .endl;
	$x.=	'	<!ELEMENT dd (#PCDATA)>' .endl;
	$x.=	'	<!ATTLIST dd k CDATA #REQUIRED>' .endl;
	$x.=	endl;
	$x.=	'	<!ELEMENT view (#PCDATA)>' .endl;
	$x.=	'	<!ATTLIST view name CDATA #REQUIRED>' .endl;
	$x.=	endl;
	$x.=	'	<!ELEMENT procedure (#PCDATA)>' .endl;
	$x.=	'	<!ATTLIST procedure name CDATA #REQUIRED>' .endl;
	$x.=	']>' .endl;

	$x.=  '<phpmiami>' .endl;
	$x.=  '	<database name="'.$database.'">' .endl;

	$sql = "SHOW FULL TABLES FROM $database";
	$result=mysqli_query($conn,$sql);
	if(!$result){return false;}

	$tables = array();
	$views = array();

	$dbs=mysqli_fetch_all($result, MYSQLI_NUM);
	foreach ($dbs as $k) {
		if($k[1]=="VIEW"){
			array_push($views, $k[0]);
		}else{
			array_push($tables, $k[0]);
		}
	}

	// GO FOR TABLES
	foreach ($tables as $k => $tab) {
		$tab_cr_sql = "SHOW CREATE TABLE $database.$tab";
		$result=mysqli_query($conn,$tab_cr_sql);
		if(!$result){return false;}

		$tab_cr=str_replace(endl,"",trim(mysqli_fetch_row($result)[1]));

		$x.= '		<table name="'.$tab.'">' .endl;
		$x.= '			<create>' .endl;
		$x.= '				'.htmlentities($tab_cr) .endl;
		$x.= '			</create>' .endl;
		$x.= '			<data>' .endl;

		// GET ROWS
		$sql = "SELECT * FROM $database.$tab";
		$result=mysqli_query($conn,$sql);
		if(!$result){return false;}

		$dbs=mysqli_fetch_all($result, MYSQLI_ASSOC);

		foreach ($dbs as $i => $v) {
			$x.= '				<dr>' .endl;
			foreach ($v as $key => $value) {
				$x.= '					<dd k="'.$key.'">'.htmlentities($value).'</dd>' .endl;
			}
			$x.= '				</dr>' .endl;
		}

		$x.= '			</data>' .endl;
		$x.= "		</table>". endl.endl;
	}

	// GO FOR VIEW
	foreach ($views as $k => $view) {
		$tab_cr_sql = "SHOW CREATE VIEW $database.$view";
		$result=mysqli_query($conn,$tab_cr_sql);
		if(!$result){return false;}

		$tab_cr=str_replace(endl,"",trim(mysqli_fetch_row($result)[1]));

		$x.= '		<view name="'.$tab.'">' .endl;
		$x.= '			'.htmlentities($tab_cr) .endl;
		$x.= '		</view>'. endl.endl;
	}

	// INCLUDE STORED PROCEDURES
	if($incStoredProcedures){
		$queryTables = mysqli_query($conn,'SHOW PROCEDURE STATUS WHERE Db="'.$database.'"'); // Viene de todas las bases de datos
		while($row = mysqli_fetch_row($queryTables)) { if($row[0]==$database) $procs[] = $row[1];}

		foreach($procs as $i=>$proc) {
			$res = mysqli_query($conn,'SHOW CREATE PROCEDURE '.$database.'.'.$proc);
			$proc_cr=str_replace("\n"," ",mysqli_fetch_row($res)[2]);

			$x.= '		<procedure name="'.$proc.'">' .endl;
			$x.= '			'.htmlentities($proc_cr) .endl;
			$x.= '		</procedure>'. endl.endl;
		}
	}

	$x.=  '	</database>' .endl;
	$x.=  '</phpmiami>' .endl;
	return $x;
}

?>
