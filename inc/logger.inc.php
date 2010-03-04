<?php
class Logger
{
	const LEVEL_INFO = 'info';
	const LEVEL_WARNING = 'warning';
	const LEVEL_ERROR = 'error';
	public $link;
	public $text;
	public $level;
	public $timeStart;
	public $timeEnd;
	
	function __construct()
	{
		$this->timeStart = 0;
		$this->timeEnd = 0;
		$this->text = "";
		$this->level = -1;
	}
	
	public function error($text)
	{
		return $this->log($text,self::LEVEL_ERROR);
	}
	
	public function warning($text)
	{
		return $this->log($text,self::LEVEL_WARNING);
	}
	
	public function info($text)
	{
		return $this->log($text,self::LEVEL_INFO);
	}

	public function start($text)
	{
		$this->timeStart = microtime(TRUE);
		return $this->log(">>>>>>>>>> START " . $text . " >>>>>>>>>",self::LEVEL_INFO);
	}
	
	public function end($text)
	{
		$this->timeEnd = microtime(TRUE);
		return $this->log("<<<<<<<<<< END " . $text . " (" . ($this->timeEnd - $this->timeStart) . " ms) <<<<<<<<<",self::LEVEL_INFO);
	}
		
	private function log($text,$level)
	{
		$this->link = fopen(LOGSDIR . date("Ymd") . '.log','a+');
		fputs($this->link,date("[D M j G:i:s Y]") . " [" . $level . '] ' . $text . "\n");
		fclose($this->link);
	}
}
?>
