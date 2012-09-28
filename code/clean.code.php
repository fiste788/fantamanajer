<?php
require_once(INCDIR . 'fileSystem.inc.php');

FileSystem::deleteFiles(DBDIR, "gz", 8);
FileSystem::deleteFiles(LOGSDIR, "log", 31);
$message->success("Operazione effettuata correttamente");
$contentTpl->assign('message',$message);
?>
