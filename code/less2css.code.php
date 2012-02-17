<?php 
require_once(INCDIR . 'lessc.inc.php');

lessc::ccompile(CSSDIR . 'layout.less', CSSDIR . 'layout.css');

$message->success("Js e css compressi con successo");	
$contentTpl->assign('message',$message);
?>
