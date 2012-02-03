<?php
abstract class Form
{
	var $dbTable;

	function __construct($tabella) {
		$this->dbTable = $tabella;
	}

}
?>
