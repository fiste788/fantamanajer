<?php
class Page
{
	var $title;
	var $roles;
	var $js;

	function __construct($title,$roles,$js) {
		$this->title = $title;
		$this->roles = $roles;
		$this->js = $js;
	}
}
?>
