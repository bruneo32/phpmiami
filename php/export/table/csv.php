<?php

function EXPORT($conn, $database, $gdt, $tablename){
	if(!isset($conn)){return false;}
	if(!isset($database)){return false;}
	if(!isset($gdt)){return false;}
	if(!isset($tablename)){return false;}

	$sql = "SELECT * FROM $database.$tablename";
	$result=mysqli_query($conn,$sql);
	if(!$result){return false;}

	$dbs=mysqli_fetch_all($result, MYSQLI_ASSOC);
	foreach ($dbs as $k => $v) {
		$x.=	implode(",",$v)."\n";
	}

	return $x;
}

?>
