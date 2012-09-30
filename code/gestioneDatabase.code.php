<?php
require_once(INCDIR . 'fileSystem.inc.php');

$result = NULL;
if(isset($_POST['query']) && !empty($_POST['query'])) {
	$querys = explode(';',$_POST['query']);
    try {
    ConnectionFactory::getFactory()->getConnection()->beginTransaction();
	foreach($querys as $key=>$val)
		if(!empty($val))
            ConnectionFactory::getFactory()->getConnection()->exec($q);
	ConnectionFactory::getFactory()->getConnection()->commit();

		$message->success('Query eseguita con successo');
    }  catch (PDOException $e) {
		ConnectionFactory::getFactory()->getConnection()->rollBack();
		$message->error('Query non valida: ' . $e->getMessage());
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
			$querys = explode(";\n", $sql);
            try {
                ConnectionFactory::getFactory()->getConnection()->beginTransaction();
                array_pop($querys);
                foreach ($querys as $key => $val)
                    if (!empty($val))
                        ConnectionFactory::getFactory()->getConnection()->exec($q);

                ConnectionFactory::getFactory()->getConnection()->commit();
                $message->success('Sincronizzazione eseguita con successo');
            } catch (PDOException $e) {
                ConnectionFactory::getFactory()->getConnection()->rollBack();
                $message->error('Errore nella sincronizzazione: ' . $e->getMessage());
                $contentTpl->assign('sql', $sql);
            }
		}
	}
}
?>
