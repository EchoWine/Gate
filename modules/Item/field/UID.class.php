<?php

class UID extends ID{

	
	/**
	 * Label
	 */
	public $label = 'UID';
	
	/**
	 * Initialize print
	 */
	public function iniPrint(){
		$this -> print = (object)[
			'list' => null,
			'view' => $this -> label,
			'form' => $this -> label,
			'inputValue' => null,
		];
	}


}
?>