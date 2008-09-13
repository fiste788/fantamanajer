<?php 
require_once(INCDIR.'giocatore.inc.php');

$giocatoreObj = new giocatore();
$giornataObj = new giornata();

$today = date("Y-m-d");
$date = $giornataObj->getDataByGiornata($giornataObj->getIdGiornataByDate());
$giorn = explode(' ',$date[2]);
$dataGiornata = $giorn[0];

/*if(isset($_GET['user']) && trim($_GET['user']) == 'admin' && isset($_GET['pass']) && trim($_GET['pass']) == md5('omordotuanuoraoarounautodromo'))
{
	if($today == $dataGiornata && date("H") == '00')
	{
*/		$giocatoreObj->doTransfert();
		$contenttpl->assign('message','Operazione effettuata correttamente');
	/*}
	else
		$contenttpl->assign('message','Non puoi effettuare l\'operazione ora');
}
else
	$contenttpl->assign('message','Non sei autorizzato a eseguire l\'operazione');*/
?>
