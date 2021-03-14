<?php
  /*
   * conn.php
   * It is imported into another *.php file and connect() is used to establish and return a connection, using the $_SESSION variables.
   */

  // Requires session_start()
  function connect(){
    $conn = mysqli_connect($_SESSION["h"],$_SESSION["u"],$_SESSION["p"],$_SESSION["db"],intval($_SESSION["po"]),$_SESSION["so"]);
    if (!$conn) {session_unset();return false;}
    return $conn;
  }
?>
