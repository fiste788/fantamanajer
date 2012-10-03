<?php
require_once(INCDBDIR . "articolo.db.inc.php");
require_once(INCDIR . "emoticon.inc.php");

if(!Request::getInstance()->has('id') || (Request::getInstance()->has('id') && Request::getInstance()->get('id') == ""))
	$articolo = new Articolo();
elseif(($articolo = Articolo::getById(Request::getInstance()->get('id'))) == FALSE)
	Request::send404();

$contentTpl->assign('articolo',$articolo);
$contentTpl->assign('emoticons',Emoticon::getEmoticons());
?>
