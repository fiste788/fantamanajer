<?php
/**
* 
* Template for testing token assignment.
* 
* @version $Id: extend.tpl.php,v 1.1.1.1 2007/04/16 13:27:50 donots Exp $
*
*/
?>
<p><?php $result = $this->plugin('example'); var_dump($result); ?></p>
<p><?php $result = $this->plugin('example_extend'); var_dump($result); ?></p>
