<?php 
require_once(INCDIR.'eventi.inc.php');

$eventiObj = new eventi();
	

$evento = 0;
$eventi = $eventiObj->getEventi($_SESSION['idLega'],NULL,0,25);
if(isset($_POST['evento']))
{
	if($_POST['evento']!= 0)
	{
		$evento = $_POST['evento'];
		$eventi = $eventiObj->getEventi($_SESSION['idLega'],$evento,0,25);
	}
}

$contenttpl->assign('evento',$evento);
$contenttpl->assign('eventi',$eventi);
?>
