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

      <input type="checkbox" id="totalCompletion" onchange="localset('autohints',this.checked);AutocompletChanged();"/><label title="The code editor shows hints automatically. You can use CTRL+SPACE to show hints." for="totalCompletion">Autohints</label>

      <input type="checkbox" id="tableHint" onchange="localset('hinttables',this.checked);GetTableHint()" checked/><label title="The code editor shows the tables of the database in the hints." for="tableHint">Hint tables</label>

      <input type="checkbox" id="procHint" onchange="localset('hintproc',this.checked);GetTableHint()"/><label title="The code editor shows the stored procedures of the database in the hints." for="procHint">Hint procedures</label>

      <hr>
      <a target="_blank" href="https://www.w3schools.com/sql/default.asp">Learn SQL</a>

      <hr>
      <a href="iedb">Import / Export Database</a>

      <div style="float:right;height: 100%;padding: 0.25em 0;">
        <span><?php echo $_SESSION["u"]."@".$_SESSION["hn"]; ?></span>
        <a href="php/102.php">Log out</a>
      </div>
    </div>

    <div id="main">

    </div>
    <script type="text/javascript">
      if(localStorage.getItem("phpmiami/autohints") === null){
        localStorage.setItem("phpmiami/autohints","false");
      }
      if(localStorage.getItem("phpmiami/hinttables") === null){
        localStorage.setItem("phpmiami/hinttables","true");
      }


      if(localStorage.getItem("phpmiami/autohints").toString() === "true"){
        document.getElementById("totalCompletion").checked=true;
      }else{document.getElementById("totalCompletion").checked=false;}
      if(localStorage.getItem("phpmiami/hinttables").toString() === "true"){
        document.getElementById("tableHint").checked=true;
      }else{document.getElementById("tableHint").checked=false;}
      if(localStorage.getItem("phpmiami/hintproc").toString() === "true"){
        document.getElementById("procHint").checked=true;
      }else{document.getElementById("procHint").checked=false;}
    </script>
  </body>
</html>
