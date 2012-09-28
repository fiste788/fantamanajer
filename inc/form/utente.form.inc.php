<?php
require_once(INCDIR . 'form.inc.php');

class UtenteForm extends Form
{
	public function check($array,$message) {
        require_once(INCDIR . 'mail.inc.php');

		$post = (object) $array;
		foreach($_POST as $key=>$val)
		{
			if($key != "passwordnew" && $key != "passwordnewrepeat" && empty($val)) {
				$message->error("Non hai compilato tutti i campi");
				return FALSE;
			}
		}
		if(!empty($post->passwordnew) && !empty($post->passwordnewrepeat))
		{
			if($post->passwordnew == $post->passwordnewrepeat)
			{
				if(strlen($post->passwordnew) < 6) {
					$message->error("La password deve essere lunga almeno 6 caratteri");
					return FALSE;
				}
			}
			else {
				$message->error("Le 2 password non corrispondono");
				return FALSE;
			}
		}
		if(!Mail::checkEmailAddress($post->mail)) {
			$message->error("Mail non corretta");
			return FALSE;
		}
		if(isset($post->nomeSquadra) && Utente::getSquadraByNome($post->nomeSquadra,$filterSquadra) != FALSE) {
			$message->error("Il nome della squadra è già presente");
			return FALSE;
		}
		return TRUE;
	}
}
?>
