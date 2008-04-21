<?php

/**
* 
* Example plugin for unit testing.
*
* @version $Id: Savant2_Plugin_example_extend.php,v 1.1.1.1 2007/04/16 13:27:50 donots Exp $
*
*/

$this->loadPlugin('example');

class Savant2_Plugin_example_extend extends Savant2_Plugin_example {
	
	var $msg = "Extended Example! ";
	
}
?>