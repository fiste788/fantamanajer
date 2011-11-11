<?php
class QuickLinks
{
	public $next;
	public $prev;
	private $request;
	
	function __construct($request)
	{
		$this->prev = FALSE;
		$this->next = FALSE;
		$this->request = $request;
	}
	
	public function set($param,$array,$title) {
	    $keys = array_keys($array);
		$current = array_search($this->request->get($param),$keys);
		if(isset($keys[($idPrec = $current - 1)]))
		{
			$this->prev->href = Links::getLink($this->request->get('p'),array($param=>$keys[$idPrec]));
			$this->prev->title = $title . ($array) ? $array[$keys[$idPrec]] : $keys[$idPrec];
		}
		if(isset($keys[($idSucc = $current + 1)]))
		{
			$this->next->href = Links::getLink($this->request->get('p'),array($param=>$keys[$idSucc]));
			$this->next->title = $title . ($array) ? $array[$keys[$idSucc]] : $keys[$idSucc];
		}
	}
}
?>
