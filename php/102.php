<?php
  /*
   * 102.php
   * Log out
   */


   session_start();

   session_unset();

   //header('Location: ../login'); // This won't work on some servers
   die('<meta http-equiv="refresh" content="0; URL=../login">');
?>
