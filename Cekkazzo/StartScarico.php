<?php
require("../inc/punteggi.inc.php");

mysql_connect("localhost","ingo_fm","banana");
mysql_select_db("test");
mysql_query("SET NAMES utf8;") or die();
mysql_query("SET CHARACTER SET utf8;")or die();


recupera_voti(39);
?>
