<!DOCTYPE html>
<?php session_start(); if(!isset($_SESSION["h"])){header('Location: login');}?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PHP Miami</title>
    <link rel="shortcut icon" type="image/x-icon" href="res/icon.jpg">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" charset="utf-8"></script>

    <link rel="stylesheet" href="codemirror/codemirror.css">
    <script src="codemirror/codemirror.js"></script>
    <script src="codemirror/sql.js"></script>
    <link rel="stylesheet" href="codemirror/show-hint.css"/>
    <script src="codemirror/show-hint.js"></script>
    <script src="codemirror/sql-hint.js"></script>
    <script src="codemirror/matchbrackets.js"></script>
  </head>
  <body onload="InstantiateCard();">
    <div id="topbar">
      <img src="res/banner.jpg" alt="" onclick="ScrollBottom()" title="Scroll bottom">
      <span style="color:var(--aux2);">DB: <?php echo $_SESSION["db"]; ?></span>

      <hr>

      <input type="checkbox" id="totalCompletion" onchange="AutocompletChanged()"/><label title="The code editor shows hints automatically. You can use CTRL+SPACE to show hints." for="totalCompletion">Autohints</label>

      <input type="checkbox" id="tableHint" onchange="GetTableHint()" checked/><label title="The code editor shows the tables of the database in the hints." for="tableHint">Hint tables</label>

      <hr>
      
      <a target="_blank" href="https://www.w3schools.com/sql/default.asp">Learn SQL</a>

      <div style="float:right;height: 100%;padding: 0.25em 0;">
        <span><?php echo $_SESSION["u"]."@".$_SESSION["hn"]; ?></span>
        <a href="php/102.php">Log out</a>
      </div>
    </div>

    <div id="main"></div>
  </body>
</html>
