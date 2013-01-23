<?php

namespace Fantamanajer\Lib;

class Ruolo {
	var $singolare;
	var $plurale;
	var $abbreviazione;

	function __construct($singolare,$plurale,$abbreviazione) {
		$this->singolare = $singolare;
		$this->plurale = $plurale;
		$this->abbreviazione = $abbreviazione;
	}

	function __toString() {
		return $this->singolare;
	}
}
?>
