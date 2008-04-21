<?php

/**
* 
* Example plugin for unit testing.
*
* @version $Id: Savant2_Plugin_example.php,v 1.1.1.1 2007/04/16 13:27:50 donots Exp $
*
*/

require_once 'Savant2/Plugin.php';

class Savant2_Plugin_example extends Savant2_Plugin {
	
	var $msg = "Example: ";
	
	function plugin()
	{
		echo $this->msg . "this is an example!";
	}
}
?>