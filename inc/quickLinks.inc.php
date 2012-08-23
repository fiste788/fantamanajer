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
	
	public function set($param,$array,$title,$other = NULL) {
	    $keys = array_keys($array);
		$current = array_search($this->request->get($param),$keys);
		if(isset($keys[($idPrec = $current - 1)]))
		{
			if($other != NULL)
				$params = array_merge(array($param=>$keys[$idPrec]),$other);
			else
				$params = array($param=>$keys[$idPrec]);
			$this->prev = new stdClass();
			$this->prev->href = Links::getLink($this->request->get('p'),$params);
			$this->prev->title = $title . ((!empty($array)) ? $array[$keys[$idPrec]] : $keys[$idPrec]);
		}
		if(isset($keys[($idSucc = $current + 1)]))
		{
			if($other != NULL)
				$params = array_merge(array($param=>$keys[$idSucc]),$other);
			else
				$params = array($param=>$keys[$idSucc]);
			$this->next = new stdClass();
			$this->next->href = Links::getLink($this->request->get('p'),$params);
			$this->next->title = $title . (!empty($array) ? $array[$keys[$idSucc]] : $keys[$idSucc]);
		}
	}
}
?>
