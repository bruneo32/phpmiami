<!DOCTYPE html>
<?php session_start(); if(!isset($_SESSION["h"])){header('Location: login');}?>
	<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Import/Export · PHP Miami</title>
		<link rel="shortcut icon" type="image/x-icon" href="res/icon.jpg">
		<link rel="stylesheet" href="style.css">

		<style>
		form{
			display:inline-block;
			vertical-align: top;
			width: calc(50% - 2px);
			box-sizing: border-box;
		}

		h2{
			text-align: center;
			font-size:1.2em;
		}
		.card table{
			width:revert;
			margin: 0 auto;
			border-spacing: 12px;
		}
		.card input{
			padding:0.25em 0.5ch !important;
		}
		.card input[type="submit"],.card input[type="button"]{
			cursor: pointer;
		}

		option.miximportable{
			color:blue;
		}
		option.unimportable{
			color:red;
		}
		</style>
		<script type="text/javascript">
		function escapeHtml(text) {
			var map = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#039;',
				"`": '&#768;'
			};

			return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
		}

		function LoadFileStr(event){
			var input = event.target;
			var reader = new FileReader();
			reader.onload = function(){
				document.getElementById("f_imp_filestr").value = escapeHtml(this.result);
			};
			reader.readAsText(input.files[0]);

			var filename=input.files[0].name.toString();
			ffm = filename.substr(filename.indexOf(".")+1);

			var formats = document.getElementById("f_imp_format").children;
			for (var i = 0; i < formats.length; i++) {
				if(formats[i].value==ffm){
					document.getElementById("f_imp_format").selectedIndex=i;
					break;
				}
			}
		}
		</script>
	</head>
	<body>
		<div id="topbar">
			<img src="res/banner.jpg" alt="" onclick="ScrollBottom()" title="Scroll bottom">
			<span style="color:var(--aux2);">DB: <?php echo $_SESSION["db"]; ?></span>

			<hr>
			<a href="main">Return to PHPMIAMI</a>

			<div style="float:right;height: 100%;padding: 0.25em 0;">
				<span><?php echo $_SESSION["u"]."@".$_SESSION["hn"]; ?></span>
				<a href="php/102.php">Log out</a>
			</div>
		</div>

		<div id="main">
			<h2>Import</h2>
			<div class="card">
				<form action="php/104.php" method="POST" style="display: revert;width: revert;">
					<table>
						<tr>
							<td colspan="2" style="text-align:center;padding-bottom:16px;">DATABASE</td>
						</tr>
						<tr>
							<td><label for="f_imp_file">File</label></td>
							<td>
								<input type="file" id="f_imp_file" name="f_imp_file" onchange="LoadFileStr(event)" accept=".sql, .csv, .tsv, .xml, .json"></td>
								<input type="hidden" id="f_imp_filestr" name="f_imp_filestr" value=""/>
							</td>
						</tr>
						<tr>
							<td><label for="f_imp_format">Format</label></td>
							<td>
								<select id="f_imp_format" name="f_imp_format">
									<option value="sql" selected>SQL</option>

									<?php

									$expf	= glob("php/import/*.php");

									foreach ($expf as $v) {
										$fname = str_replace(".php","",basename($v));

										echo "<option value='".strtolower($fname)."'>".strtoupper($fname)."</option>";
									}

									?>
								</select>
							</td>
						</tr>
						<tr>
							<td style="text-align:center;" colspan="2"><input type="submit" value="IMPORT"/></td>
						</tr>
					</table>
				</form>
			</div>

			<br/>
			<h2>Export</h2>
			<div class="card">

				<form action="php/105.php" method="POST" style="border-right:1px solid rgba(0,0,0,0.3);">
					<table>
						<tr>
							<td colspan="2" style="text-align:center;padding-bottom:16px;">DATABASE</td>
						</tr>
						<tr>
							<td><label for="f_exp_db">DB</label></td>
							<td>
								<select id="f_exp_db" name="f_exp_db">
									<option selected disabled value="-">Select database to export</option>
									<?php

									require("php/config.php");
									if(!$DEBUG_MODE){error_reporting(E_ALL ^ E_WARNING);} // Supress warnings

									require("php/conn.php");

									$conn=connect();
									if(!$conn){die("</select><b>[System]</b> Conection failed - ".mysqli_connect_errno()."<br>".mysqli_connect_error());}

									$q="SHOW DATABASES";
									$result=mysqli_query($conn,$q);
									if(!$result){die("</select><b>[System]</b> Error getting DATABASES");}

									$dbs=mysqli_fetch_all($result, MYSQLI_NUM);
									for ($i=0; $i < count($dbs); $i++) {
										echo "<option>".$dbs[$i][0]."</option>";
									}

									?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="f_exp_sp">Include stored procedures</label></td>
							<td><input type="checkbox" id="f_exp_sp" name="f_exp_sp" checked></td>
						</tr>

						<tr>
							<td style="padding-top:1em;"><label for="f_exp_name">Filename</label></td>
							<td style="padding-top:1em;"><input type="text" placeholder="mydatabase.sql" id="f_exp_name" name="f_exp_name" required></td>
						</tr>

						<tr>
							<td><label title="The red ones are not importable." style="cursor:help;" for="f_exp_format">Format *</label></td>
							<td>
								<select id="f_exp_format" name="f_exp_format">
									<option value="sql" selected>SQL</option>

									<?php

									$expf	= glob("php/export/*.php");

									foreach ($expf as $v) {
										$fname = str_replace(".php","",basename($v));
										$ofname= $fname;
										$isImp = ($fname[0]=="_");
										if($isImp){$fname=substr($fname,1);}

										echo "<option value='".strtolower($ofname)."'".($isImp ? " class='unimportable'" : "").">".strtoupper($fname)."</option>";
									}

									?>

								</select>
							</td>
						</tr>

						<tr>
							<td><label for="f_exp_charset">Charset</label></td>
							<td>
								<select id="f_exp_charset" name="f_exp_charset">
									<option value="utf-8" selected>utf-8</option>
								</select>
							</td>
						</tr>

						<tr>
							<td style="text-align:center;padding-top:1em;" colspan="2"><input type="submit" id="vbtn" value="EXPORT DATABASE"/></td>
						</tr>
					</table>
				</form>

				<form action="php/105.php" method="POST">
					<table>
						<tr>
							<td colspan="2" style="text-align:center;padding-bottom:16px;">TABLE</td>
						</tr>
						<tr>
							<td><label for="f_exp_db">DB</label></td>
							<td>
								<select id="f_exp_db" name="f_exp_db">
									<option selected disabled value="-">Select database to export</option>
									<?php

									$q="SHOW DATABASES";
									$result=mysqli_query($conn,$q);
									if(!$result){die("</select><b>[System]</b> Error getting DATABASES");}

									$dbs=mysqli_fetch_all($result, MYSQLI_NUM);
									for ($i=0; $i < count($dbs); $i++) {
										echo "<option>".$dbs[$i][0]."</option>";
									}

									?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="f_exp_table">TABLE</label></td>
							<td><input type="text" placeholder="table_name" id="f_exp_table" name="f_exp_table" required></td>
						</tr>

						<tr>
							<td style="padding-top:1em;"><label for="f_exp_name2">Filename</label></td>
							<td style="padding-top:1em;"><input type="text" placeholder="mytable.csv" id="f_exp_name2" name="f_exp_name" required></td>
						</tr>

						<tr>
							<td><label for="f_exp_format2">Format</label></td>
							<td>
								<select id="f_exp_format2" name="f_exp_format">
									<?php

									$expf	= glob("php/export/table/*.php");

									foreach ($expf as $v) {
										$fname = str_replace(".php","",basename($v));

										echo "<option value='".strtolower($fname)."'>".strtoupper($fname)."</option>";
									}

									?>
								</select>
							</td>
						</tr>

						<tr>
							<td><label for="f_exp_charset2">Charset</label></td>
							<td>
								<select id="f_exp_charset2" name="f_exp_charset">
									<option value="utf-8" selected>utf-8</option>
								</select>
							</td>
						</tr>

						<tr>
							<td style="text-align:center;padding-top:1em;" colspan="2"><input type="submit" id="vbtn" value="EXPORT TABLE"/></td>
						</tr>
					</table>
				</form>

			</div>

		</div>
	</body>
	</html>
