<?php
class links
{
	function getLink($page,$arrayParam = NULL)
	{
		if(MODREWRITE)
		{
			$link = '/'.$page;
			if($arrayParam != NULL)
				$link .= '/'.implode('/',$arrayParam);
			$link .= '.html';
		}
		else
		{
			$link = 'index.php?p='.$page;
			if($arrayParam != NULL)
			{
				foreach($arrayParam as $key=>$val)
					$link .= '&amp;'.$key."=".$val;
			}
		}
		return $link;
	}
}
?>