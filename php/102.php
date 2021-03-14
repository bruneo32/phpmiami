<?php
  /*
   * 102.php
   * Log out
   */

   session_start();

   session_unset();

   //header('Location: ../login');
   die('<meta http-equiv="refresh" content="0; URL=../login">');
?>
