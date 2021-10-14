<?php

function EXPORT($conn, $database, $gdt, $incStoredProcedures){
	if(!isset($conn)){return false;}
	if(!isset($database)){return false;}
	if(!isset($gdt)){return false;}
	if(!isset($incStoredProcedures)){return false;}

	// GET TABLES
	$sql = "SHOW FULL TABLES FROM $database WHERE Table_type<>'view'";
	$result=mysqli_query($conn,$sql);
	if(!$result){return false;}

	$tables = array();

	$dbs=mysqli_fetch_all($result, MYSQLI_NUM);
	foreach ($dbs as $k) {
		array_push($tables, $k[0]);
	}

	// HEADER
	$x="&lt;?php" .endl;

	$x.= "/**\n * PHP EXPORT FROM PHPMIAMI" .endl;
	$x.= " * " .endl;
	$x.= " * Host: \t\t\t\t". $_SESSION['h'] .endl;
	$x.= " * Generation time: \t". $gdt .endl;
	$x.= " * PHP Version: \t\t". phpversion() .endl;
	$x.= " */".endl.endl.endl;

	$x.= "/**\n * Database $database\n */" .endl;
	$x.= endl;

	// for tables
	foreach ($tables as $tab) {
		$x.= "/* $database.$tab */" .endl;
		$x.= "\$".$database."['".$tab."'] = array(" .endl;

		// GET ROWS
		$sql = "SELECT * FROM $database.$tab";
		$result=mysqli_query($conn,$sql);
		if(!$result){
			return false;
		}

		$dbs=mysqli_fetch_all($result, MYSQLI_ASSOC);
		if(count($dbs)==0){
			$x.= ");" .endl.endl;
			continue;
		}

		$ak=array_keys($dbs[0]);
		foreach ($dbs as $j => $v) {
			$x.= "\tarray(";
			for ($i=0; $i < count($v); $i++) {
				$k = $ak[$i];
				$x.= "'$k' => '$v[$k]'";
				if($i!=count($v)-1){$x.= ", ";}
			}
			$x.= ")" .($j!=count($dbs)-1 ? ",":"") .endl;
		}


		$x.= ");" .endl.endl;
	}

	$x.="?&gt;" .endl;

	return $x;
}

?>
