<?php
/*
* Extracted from https://github.com/ttodua/useful-php-scripts
* (MODIFIED)
*/


/*
##### EXAMPLE #####
EXPORT_DATABASE("localhost","user","pass","db_name" );

##### Notes #####
* (optional) 5th parameter: to backup specific tables only,like: array("mytable1","mytable2",...)
* (optional) 6th parameter: backup filename (otherwise, it creates random name)
* IMPORTANT NOTE ! Many people replaces strings in SQL file, which is not recommended. READ THIS:  http://puvox.software/tools/wordpress-migrator
* If you need, you can check "import.php" too
*/

// by https://github.com/ttodua/useful-php-scripts //
function EXPORT_DATABASE($host,$user,$pass,$name, $incStoredProcedures,       $tables=false, $backup_name=false)
{
	set_time_limit(3000);
	$mysqli = new mysqli($host,$user,$pass,$name);
	$mysqli->select_db($name);
	$mysqli->query("SET NAMES 'utf8'");

	$queryTables = $mysqli->query('SHOW FULL TABLES');
	while($row = $queryTables->fetch_row()) { $target_tables[] = $row[0]; $target_tables_type[] = $row[1];}
	if($tables !== false) { $target_tables = array_intersect( $target_tables, $tables); }

	$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\nSET AUTOCOMMIT = 0;\nSTART TRANSACTION;\nSET time_zone = \"+00:00\";\n\n\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n/*!40101 SET NAMES utf8 */;\n\n\n/*\n * Database: `".$name."`\n */\n";

	$content.= "DROP DATABASE IF EXISTS `".$name."`;";
	$content.= "\n".$mysqli->query('SHOW CREATE DATABASE '.$name)->fetch_row()[1].";";
	$content.= "\nUSE `".$name."`;\n\n\n";

	for ($o=0; $o < count($target_tables); $o++) {
		$table = $target_tables[$o];
		$ttype = $target_tables_type[$o];

		if (empty($table)){ continue; }

		$result	= $mysqli->query('SELECT * FROM '.$table.'');
		$fields_amount=$result->field_count;
		$rows_num=$mysqli->affected_rows;
		$res = $mysqli->query('SHOW CREATE TABLE '.$table);
		$TableMLine=$res->fetch_row();

		$content.= "\nDROP ".($ttype=="VIEW" ? "VIEW" : "TABLE")." IF EXISTS `$table`;\n";
		$content .= $TableMLine[1].";\n";
		$TableMLine[1]='CREATE TABLE '.$TableMLine[1];

		if($ttype=="VIEW"){continue;}

		for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
			while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )	{$content .= "\nINSERT INTO `".$table."` VALUES";}
				$content .= "\n(";
				for($j=0; $j<$fields_amount; $j++){
					$row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );

					if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}
					else{$content .= '""';}
					if ($j<($fields_amount-1)){$content.= ',';}
				}
				$content .=")";

				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";\n\n\n";}
				else {$content .= ",";}	$st_counter=$st_counter+1;
			}
		}
	}


	// INCLUDE STORED PROCEDURES
	if($incStoredProcedures){
		$content.= "\n";

		$queryTables = $mysqli->query('SHOW PROCEDURE STATUS');
		while($row = $queryTables->fetch_row()) { $procs[] = $row[1];}

		foreach($procs as $table) {
			$res = $mysqli->query('SHOW CREATE PROCEDURE '.$table);
			$TableMLine=$res->fetch_row()[2];

			$content.= "\nDROP PROCEDURE IF EXISTS `$table`;\n";
			$content .= $TableMLine.";\n";
		}
	}


	// TAIL
	$content .= "\nCOMMIT;\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";

	return $content;

	/*
	$backup_name = $backup_name ? $backup_name : $name.'___('.date('H-i-s').'_'.date('d-m-Y').').sql';
	ob_get_clean(); header('Content-Type: application/octet-stream');  header("Content-Transfer-Encoding: Binary");  header('Content-Length: '. (function_exists('mb_strlen') ? mb_strlen($content, '8bit'): strlen($content)) );    header("Content-disposition: attachment; filename=\"".$backup_name."\"");
	echo $content; exit;
	*/
}
?>
