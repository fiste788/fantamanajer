<?php

class string
{
	var $string;
	
	//Constructor
	function string($text)
	{
		$this->string = $text;
	}

	//String cleaner from bad characters
	function stringCleaner()
	{
		if (!get_magic_quotes_gpc()) 
		{
    	$this->string = addslashes($this->string);
		} 
		$this->string = strtr($this->string, array('_' => '\_', '%' => '\%'));
	}
	
	//String cleaner from bad characters and HTML
	function stringCleanerHtml()
	{
		if (!get_magic_quotes_gpc()) 
		{
    	$this->string = addslashes($this->string);
		} 
		$this->string = strtr($this->string, array('_' => '\_', '%' => '\%'));
		$this->string = strip_tags($this->string);
	}
}

?>