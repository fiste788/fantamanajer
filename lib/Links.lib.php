<?php

namespace Fantamanajer;

class Links {
	public static function getLink($page,$arrayParam = NULL) {
		if(MODREWRITE) {
			$link = '/' . $page;
			if($arrayParam != NULL)
				$link .= '/' . implode('/',$arrayParam);
		} else {
			$link = 'index.php?p='.$page;
			if($arrayParam != NULL) {
				foreach($arrayParam as $key => $val)
                    if($val != "")
                        $link .= '&amp;' . $key . "=" . $val;
			}
		}
		return $link;
	}
}
?>
