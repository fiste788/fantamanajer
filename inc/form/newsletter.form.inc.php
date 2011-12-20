<?php
require_once(INCDIR . 'form.inc.php');

class NewsletterForm extends Form
{
	var $object;
	var $text;
	var $type;

	public function check($array,$message) {
        foreach($array as $key=>$val) {
			if(empty($val)) {
				$message->error("Non hai compilato tutti i campi");
				return FALSE;
			}
		}
	}
}
?>
