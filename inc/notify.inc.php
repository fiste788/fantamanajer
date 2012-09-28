<?php
class Notify
{
    const LEVEL_LOW = 0;
	const LEVEL_MEDIUM = 1;
	const LEVEL_HIGH = 2;
	public $text;
	public $level;
    public $link;
	
	function __construct($level,$text,$link)
	{
		$this->text = $text;
		$this->level = $level;
        $this->link = $link;
	}
}
?>
