<?php 
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

if(!$request->has('id') || ($request->has('id') && $request->get('id') == ""))
	$articolo = new Articolo();
elseif(($articolo = Articolo::getById($request->get('id'))) === FALSE)
	Request::send404();

$contentTpl->assign('articolo',$articolo);
$contentTpl->assign('emoticons',Emoticon::getEmoticons());
?>
