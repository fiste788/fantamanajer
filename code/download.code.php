<?php 
/*require_once(INCDIR.'giornata.inc.php');
if(isset($_POST['file']))
 $_SESSION['file']=$_POST['file'];
else
 $_SESSION['file']=1;
$giornataObj = new giornata();
$contenttpl->assign('lastgiornata',$giornataObj->getIdGiornataByDate());     
$contenttpl->assign('giornatasel',$_SESSION['file']);
$path=VOTIDIR."Giornata".$_SESSION['file'].".csv";                           
if(!file_exists($path))
 $contenttpl->assign('path',"error");
else
 $contenttpl->assign('pathdownload',$path);*/
 
require_once(INCDIR.'fileSystem.inc.php');
$fileObj = new fileSystem();
$filesvoti=$fileObj->getFileIntoFolder(VOTIDIR);
$contenttpl->assign('filesvoti',$filesvoti);
if(isset($_POST['file']))
 $_SESSION['file']=$_POST['file'];
else
 $_SESSION['file']=$filesvoti[0];
$contenttpl->assign('filesel',$_SESSION['file']);
$contenttpl->assign('downloadpath',VOTIDIR.$_SESSION['file']);
?>
