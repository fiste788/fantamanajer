<?php
class message
{
	const LEVEL_SUCCESS = 0;
	const LEVEL_WARNING = 1;
	const LEVEL_ERROR = 2;
	public $show;
	public $text;
	public $level;
	
	function __construct()
	{
		$this->show = FALSE;
		$this->text = "";
		$this->level = -1;
	}
	
	public function error($text) {
		return $this->createMessage($text,self::LEVEL_ERROR);
	}
	
	public function warning($text) {
		return $this->createMessage($text,self::LEVEL_WARNING);
	}
	
	public function success($text) {
		return $this->createMessage($text,self::LEVEL_SUCCESS);
	}
	
	private function createMessage($text,$level)
	{
		$this->text = $text;
		$this->level = $level;
		$this->show = TRUE;
	}
}
?>
