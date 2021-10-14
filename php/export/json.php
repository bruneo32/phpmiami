<?php

function EXPORT($conn, $database, $gdt, $incStoredProcedures){
	if(!isset($conn)){return false;}
	if(!isset($database)){return false;}
	if(!isset($gdt)){return false;}
	if(!isset($incStoredProcedures)){return false;}

	$x= "[".endl;
	$x.= '	{'.endl.'		"type": "header",'.endl.'		"comment": "JSON EXPORT FROM PHPMIAMI",'.endl.endl.'		"host": "'.$_SESSION["h"].'",'.endl.'		"generation_time": "'.$gdt.'",'.endl.'		"php_version": "'.phpversion().'"'.endl.'	},'.endl;

	$x.= '	{"type":"database", "name":"'.$database.'"},'.endl;
	$x.= endl;

	$sql = "SHOW FULL TABLES FROM $database";
	$result=mysqli_query($conn,$sql);
	if(!$result){return false;}

	$tables = array();
	$ttables = array();

	$dbs=mysqli_fetch_all($result, MYSQLI_NUM);
	foreach ($dbs as $k) {
		array_push($tables, $k[0]);
		array_push($ttables, $k[1]);
	}

	// GO FOR TABLES
	foreach ($tables as $k => $tab) {
		$tab_cr_sql = "SHOW CREATE TABLE $database.$tab";
		$result=mysqli_query($conn,$tab_cr_sql);
		if(!$result){return false;}

		$tab_cr=str_replace(endl,"",trim(mysqli_fetch_row($result)[1]));

		$x.= '	{"type":"'.($ttables[$k]=="VIEW" ? "view" : "table").'","name":"'.$tab.'","database":"'.$database.'","create":"'.$tab_cr.'"';

			if($ttables[$k]!="VIEW"){
				$x.=',"data":'.endl.'		['.endl;

				// GET ROWS
				$sql = "SELECT * FROM $database.$tab";
				$result=mysqli_query($conn,$sql);
				if(!$result){return false;}

				$dbs=mysqli_fetch_all($result, MYSQLI_ASSOC);

				foreach ($dbs as $i => $v) {
					$x.= "			".json_encode($v,JSON_UNESCAPED_UNICODE|JSON_HEX_QUOT).($i!=count($dbs)-1 ? ",".endl:"");
				}
				$x.= endl."		]". endl."	";
			}

			$x.= "}".($k!=count($tables)-1 ? ",".endl:"");
		}


		// INCLUDE STORED PROCEDURES
		if($incStoredProcedures){
			$x.= ",\n\n";

			$queryTables = mysqli_query($conn,'SHOW PROCEDURE STATUS WHERE Db="'.$database.'"'); // Viene de todas las bases de datos
			while($row = mysqli_fetch_row($queryTables)) { if($row[0]==$database) $procs[] = $row[1];}

			foreach($procs as $i=>$proc) {
				$res = mysqli_query($conn,'SHOW CREATE PROCEDURE '.$database.'.'.$proc);
				$proc_cr=str_replace("\"","\\\"",str_replace("\n"," ",mysqli_fetch_row($res)[2]));

				$x.= '	{"type":"procedure","name":"'.$proc.'","database":"'.$database.'","create":"'.$proc_cr.'"}';
				$x.=	($i!=count($procs)-1 ? ",":"").endl;
			}
		}

		$x.= "]".endl;

		return $x;
	}

	?>
