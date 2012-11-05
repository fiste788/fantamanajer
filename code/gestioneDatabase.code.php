<?php 
require_once(INCDIR . 'fileSystem.inc.php');

$result = NULL;
if(isset($_POST['query']) && !empty($_POST['query'])) {
	$querys = explode(';',$_POST['query']);
	$dbConnection->startTransaction();
	foreach($querys as $key=>$val)
		if(!empty($val))
			mysql_query($val) or $err = MYSQL_ERRNO() . " - " . MYSQL_ERROR();
	if(!isset($err)) {
		$dbConnection->commit();
		$message->success('Query eseguita con successo');
	}
	else {
		$dbConnection->rollback();
		$message->error('Query non valida: ' . $err);
		$contentTpl->assign('sql',$_POST['query']);
	}
}
if($request->has('action')) {
	if($request->get('action') == 'optimize'){
		if(dbObj::dbOptimize())
			$message->success('Database ottimizzato con successo');
	}	
	if($request->has('action') == 'sincronize') {
		$sql = fileSystem::getLastBackup();
		if(!$sql)
			$message->warning('Errore nel recupero dell\'ultimo backup');
		else {
			$querys = explode(";\n",$sql);
			Db::startTransaction();
			array_pop($querys);
			$err = "";
			foreach($querys as $key=>$val)
				if(!empty($val))
					mysql_query($val) or $err .= MYSQL_ERRNO() . " - " . MYSQL_ERROR() . " " . $val . "\n\n";
			if(empty($err)) {
				$dbConnection->commit();
				$message->success('Sincronizzazione eseguita con successo');
			} else {
				echo $err;
				$dbConnection->rollback();
				$message->error('Errore nella sincronizzazione: ' . $err);
				$contentTpl->assign('sql',$sql);
			}
		}
	}
}
?>
