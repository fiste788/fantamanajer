<?php 
require_once(INCDIR.'db.inc.php');

$dbObj = new db();

if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'optimize': $result = $dbObj->DbOptimize(); break;
		default: break;
	}
	if($result)
	{
		$messaggio[0] = 0;
		$messaggio[1] = 'Operazione effettuata con successo';
		$contenttpl->assign('messaggio',$messaggio);
	}
}
?>
