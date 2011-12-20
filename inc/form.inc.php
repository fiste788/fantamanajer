<?php
abstract class Form
{
	var $dbTable;

	function __construct($tabella) {
		$this->dbTable = $tabella;
	}

	/*
	 * Richiama la funzione check specifica della classe e in caso ritorni false setta
	 * dall'array con valori raw senza fare il cast per mantenere le variabili non corrette
	 */
	public function validate() {
		$return = $this->check($postArray = $GLOBALS['request']->getRawData('post'),$GLOBALS['message']);
  		$this->fromArray($postArray,$return);
        return $return;
	}
	
	public function fromArray($array,$raw = FALSE) {
		$vars = get_object_vars($this->dbTable);
		$GLOBALS['firePHP']->log($vars);
		foreach($array as $key=>$value) {
			if(array_key_exists($key,$vars) && !is_null($value)) {
			    if(!$raw && method_exists($this->dbTable,$methodName = 'set' . ucfirst($vars[$key])))
				    $this->$methodName($value);
                else
                    $this->$key = $value;
            }
		}
	}

	public abstract function check($array,$message);
}
?>
