<?php
  /*
   * config.php
   * Basic configuration of phpmiami server side
   */


  $DEBUG_MODE=false;

  $_CONFIG["allow_localhost"] = (($_SERVER["SERVER_ADDR"]=="::1" || $_SERVER["SERVER_ADDR"]=="127.0.0.1") ? true:false);
  /* Enable the following line to allow localhost access. */
  //$_CONFIG["allow_localhost"]=true;

?>
