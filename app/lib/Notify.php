<?php

namespace Fantamanajer\Lib;

class Notify {

    const LEVEL_LOW = 0;
	const LEVEL_MEDIUM = 1;
	const LEVEL_HIGH = 2;

    /**
     *
     * @var string
     */
	public $text;

    /**
     *
     * @var int
     */
	public $level;

    /**
     *
     * @var string
     */
    public $link;

	function __construct($level,$text,$link) {
		$this->text = $text;
		$this->level = $level;
        $this->link = $link;
	}
}

 
