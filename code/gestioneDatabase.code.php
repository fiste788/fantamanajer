<?php 
require_once(INCDIR.'db.inc.php');

$dbObj = new db();
$result = NULL;
if(isset($_POST['query']) && !empty($_POST['query']))
{
	if(mysql_query($_POST['query']))
	{
		$messaggio[0] = 0;
		$messaggio[1] = 'Operazione effettuata con successo';
	}
	else
	{
		$messaggio[0] = 1;
		$messaggio[1] = 'Query non valida';
	}
	$contenttpl->assign('messaggio',$messaggio);
}
if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'optimize': $result = $dbObj->DbOptimize(); break;
		case 'sincronize': $sql = $dbObj->sincronize(); $contenttpl->assign('sql',$sql);break;
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
