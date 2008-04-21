<?php

/**
* 
* Tests default plugins
*
* @version $Id: 8_extend.php,v 1.1.1.1 2007/04/16 13:27:50 donots Exp $
* 
*/

error_reporting(E_ALL);

require_once 'Savant2.php';

$conf = array(
	'template_path' => 'templates',
	'resource_path' => 'resources'
);

$savant =& new Savant2($conf);

$savant->display('extend.tpl.php');

?>