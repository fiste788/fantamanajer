<?php 
require(INCDIR . 'savant/Savant3.php');

class MySavant3 extends Savant3 {
	public function assign() {
		$arg0 = @func_get_arg(0);
		$arg1 = @func_get_arg(1);
		
		if(!isset($this->$arg0))
		    parent::assign($arg0,$arg1);
		else
		    return;
	}
	
	public function assignForce() {
		$arg0 = @func_get_arg(0);
		$arg1 = @func_get_arg(1);
	
		parent::assign($arg0,$arg1);
	}
}
?>
