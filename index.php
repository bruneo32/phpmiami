<!DOCTYPE html>
<?php session_start(); if(isset($_SESSION["h"])){header('Location: main');}?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Log in · PHP Miami</title>
    <link rel="shortcut icon" type="image/x-icon" href="res/icon.jpg">
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="#" id="externalStylesheet">

    <style>
      img{
        margin: 16px;
      }
      form{
        width: fit-content;
        width: -moz-fit-content;
        text-align:center;
        margin-left: 50vw;
        transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -webkit-transform: translateX(-50%);
      }
      form h2{
        font-weight: normal;
        font-style: italic;
        padding-bottom: 2em;
      }
      form table{
        box-sizing: border-box;
        width:25vw;
        background-color: var(--color2);
        border:1px solid var(--aux2);
        padding:16px 48px 24px 48px;
        margin:6px auto;
      }
      form table td{
        min-width:128px;
      }
      form table th{padding-bottom: 1em;}
      form table tr td:first-child{
        text-align: left;
      }
      .botbar{
        position: fixed;
        left:0;bottom:0;
        width:100%;
        padding:8px;
        box-sizing: border-box;
      }
      .botbar *{
        padding:6px;
        cursor: pointer;
        text-decoration: underline;
      }
      .botbar img{
        padding:0;
        margin:0;
        height:1.1em;
        vertical-align: baseline;
      }
    </style>
	<script src="theme.js" charset="utf-8"></script>
  </head>
  <body onload="LoadStyles()">
    <form action="php/100.php" method="GET">
      <img src="res/banner.jpg" alt="">
      <h2>Open source database administrator via SQL over the web.</h2>
      <table>
        <tr>
          <th colspan="2">Required</th>
        </tr>
        <tr>
          <td><label for="id_h">Host</label></td>
          <td><input type="text" name="h" id="id_h" value=""></td>
        </tr>
        <tr>
          <td><label for="id_u">Username</label></td>
          <td><input type="text" name="u" id="id_u" value=""></td>
        </tr>
        <tr>
          <td><label for="id_p">Password</label></td>
          <td><input type="password" name="p" id="id_p" value=""></td>
        </tr>
      </table>
      <table>
        <tr>
          <th colspan="2">Optional</th>
        </tr>
        <tr>
          <td><label for="id_db">DB Name</label></td>
          <td><input type="text" name="db" id="id_db" value=""></td>
        </tr>
        <tr>
          <td><label for="id_po">Port</label></td>
          <td><input type="text" name="po" id="id_po" value=""></td>
        </tr>
        <tr>
          <td><label for="id_so">Socket</label></td>
          <td><input type="text" name="so" id="id_so" value=""></td>
        </tr>
      </table>
      <div class="center" style="padding:16px 64px;"><input class="button" type="submit" value="Connect"></div>
      <br>
      <!-- phpmiami is a free software tool written in PHP, intended to handle the administration of MySQL executing SQL statements over the Web. phpmiami supports a wide range of operations on MySQL and MariaDB. -->
    </form>

    <br>

    <div class="botbar">
      <a target="_blank" href="doc/localhost.html">Localhost</a>
      <a target="_blank" href="doc/connect_remote.html">Connect to remote host</a>

      <div style="float:right;text-decoration:none;padding:0;">
        <a target="_blank" href="https://github.com/bruneo32/phpmiami">Source on GitHub <img src="res/github.png" alt=""></a>
        <a onclick="window.open('licenses.html', 'MsgWindow', 'width=640,height=480');">Licenses & Credits</a>
      </div>
    </div>

  </body>
</html>
