<!DOCTYPE html>
<?php session_start(); if(!isset($_SESSION["h"])){header('Location: login');}?>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>PHP Miami</title>
		<link rel="shortcut icon" type="image/x-icon" href="res/icon.jpg">
		<link rel="stylesheet" href="#" id="externalStylesheet">
		<link rel="stylesheet" href="style.css">
		<script src="script.js" charset="utf-8"></script>
		<script src="theme.js" charset="utf-8"></script>

		<link rel="stylesheet" href="codemirror/codemirror.css">
		<script src="codemirror/codemirror.js"></script>
		<script src="codemirror/sql.js"></script>
		<link rel="stylesheet" href="codemirror/show-hint.css"/>
		<script src="codemirror/show-hint.js"></script>
		<script src="codemirror/sql-hint.js"></script>
		<script src="codemirror/matchbrackets.js"></script>
	</head>
	<body onload="LoadStyles();InstantiateCard();">
		<div class="styles_btn" onclick="ShowHideStylesPanel()" title="Show/Hide Custom Styles Panel"><img src="res/styles.png" alt=""/></div>
		<div id="styles_panel" style="width:0vw;opacity:0;">
		<h1>Custom Styles</h1>

		<table>
			<tr>
				<td style="text-align:center;">
					<select class="button" id="__cs_theme" style="color:var(--aux2);" onchange="SetTheme(this.selectedIndex-1)">
						<option selected disabled>Select Theme</option>
						<option>phpmiami Classic</option>
						<option>Flower</option>
						<option>Sand</option>
						<option>Sky</option>
						<option>Rustic</option>
						<option>Matrix</option>
						<option>Darcula</option>
						<option>Dracula</option>
					</select>
				</td>
				<td style="text-align:center;">
					<input type="button" onclick="SaveStyles()" style="color:var(--aux2);" class="button" value="Save changes">
				</td>
			</tr>

			<!-- Separator -->
			<tr><td></td></tr>

			<tr>
				<td>CodeMirror Theme CSS</td>
				<td><input type="text" style="color:var(--aux2);" placeholder="Default" id="__cs_extCM" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>External Stylesheet</td>
				<td><input type="text" style="color:var(--aux2);" placeholder="No import" id="__cs_extSS" onchange="SetStylesBtn()"></td>
			</tr>

			<!-- Separator -->
			<tr><td></td></tr>

			<tr>
				<td>Font Monospace</td>
				<td><input type="text" style="font-family:var(--ffmono),monospace;color:var(--aux2);" placeholder="Monospace Font" id="__cs_ffmono" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Font Serif</td>
				<td><input type="text" style="font-family:var(--ffserif),serif;color:var(--aux2);" placeholder="Serif Font" id="__cs_ffserif" onchange="SetStylesBtn()"></td>
			</tr>

			<!-- Separator -->
			<tr><td></td></tr>

			<tr>
				<td>Color &ensp; 1</td>
				<td><input type="color" id="__cs_color_1" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color &ensp; 2</td>
				<td><input type="color" id="__cs_color_2" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color &ensp; 3</td>
				<td><input type="color" id="__cs_color_3" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color &ensp; 4</td>
				<td><input type="color" id="__cs_color_4" onchange="SetStylesBtn()"></td>
			</tr>

			<!-- Separator -->
			<tr><td></td></tr>

			<tr>
				<td>Color &ensp; B1</td>
				<td><input type="color" id="__cs_color_11" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color &ensp; B2</td>
				<td><input type="color" id="__cs_color_22" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color &ensp; B3</td>
				<td><input type="color" id="__cs_color_33" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color &ensp; B4</td>
				<td><input type="color" id="__cs_color_44" onchange="SetStylesBtn()"></td>
			</tr>

			<!-- Separator -->
			<tr><td></td></tr>

			<tr>
				<td>Color AUX &ensp; 1</td>
				<td><input type="color" id="__cs_color_aux1" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color AUX &ensp; 2</td>
				<td><input type="color" id="__cs_color_aux2" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color AUX &ensp; Bright</td>
				<td><input type="color" id="__cs_color_aux11" onchange="SetStylesBtn()"></td>
			</tr>
			<tr>
				<td>Color AUX &ensp; Dark</td>
				<td><input type="color" id="__cs_color_aux22" onchange="SetStylesBtn()"></td>
			</tr>
		</table>
		</div>


		<div id="topbar">
			<img src="res/banner.jpg" alt="" onclick="ScrollBottom()" title="Scroll bottom">
			<span style="color:var(--aux2);">DB: <input type="text" placeholder="Database name..." value="<?php echo $_SESSION["db"];?>" id="_dbname" onfocusout="adbchange(this.value)"></span>

			<hr>

			<input type="checkbox" id="totalCompletion" onchange="localset('autohints',this.checked);AutocompletChanged();"/><label title="The code editor shows hints automatically. You can use CTRL+SPACE to show hints." for="totalCompletion">Autohints</label>

			<input type="checkbox" id="tableHint" onchange="localset('hinttables',this.checked);GetTableHint()" checked/><label title="The code editor shows the tables of the database in the hints." for="tableHint">Hint tables</label>

			<input type="checkbox" id="procHint" onchange="localset('hintproc',this.checked);GetTableHint()"/><label title="The code editor shows the stored procedures of the database in the hints." for="procHint">Hint procedures</label>

			<hr>
			<a target="_blank" href="https://www.w3schools.com/sql/default.asp">Learn SQL</a>

			<hr>
			<a href="iedb">Import / Export Database</a>

			<div class="rside">
				<span><?php echo $_SESSION["u"]."@".$_SESSION["hn"]; ?></span>
				<a href="php/102.php">Log out</a>
			</div>
		</div>

		<div id="main" onmousedown="ShowHideStylesPanel(false)">

		</div>
		<script type="text/javascript">
			if(localStorage.getItem("phpmiami/autohints") === null){
				localStorage.setItem("phpmiami/autohints","false");
			}
			if(localStorage.getItem("phpmiami/hinttables") === null){
				localStorage.setItem("phpmiami/hinttables","true");
			}
			if(localStorage.getItem("phpmiami/hintproc") === null){
				localStorage.setItem("phpmiami/hintproc","false");
			}

			if(localStorage.getItem("phpmiami/autohints")?.toString() === "true"){
				document.getElementById("totalCompletion").checked=true;
			}else{document.getElementById("totalCompletion").checked=false;}
			if(localStorage.getItem("phpmiami/hinttables")?.toString() === "true"){
				document.getElementById("tableHint").checked=true;
			}else{document.getElementById("tableHint").checked=false;}
			if(localStorage.getItem("phpmiami/hintproc")?.toString() === "true"){
				document.getElementById("procHint").checked=true;
			}else{document.getElementById("procHint").checked=false;}
		</script>
	</body>
</html>
