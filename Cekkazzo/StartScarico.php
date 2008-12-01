<?php
require("../inc/fileSystem.inc.php");
require("../inc/formazione.inc.php");
include 'Functions.php';
mysql_connect("localhost","ingo_fm","banana");
mysql_select_db("test");
mysql_query("SET NAMES utf8;") or die();
mysql_query("SET CHARACTER SET utf8;")or die();
$fileSystemObj = new fileSystem;
$fileSystemObj->scaricaVotiCsv(14);
//recupera_voti(38);
//$puntobj->calcolaPunti(26,1,"");
//importa_giocatori();

?>
