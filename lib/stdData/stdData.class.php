<?php

class stdData extends stdObject{

	public $label;

	public $form;

	public $value;

	public function __construct($l = null,$f = null,$v = null){

		$this -> setLabel($l);
		$this -> setForm($f);
		$this -> setValue($f,$v);
	}

	public function setLabel($v = 'Default Label'){
		$this -> label = $v;
	}


	public function setForm($v = ''){
		$this -> form = $v;
	}

	public function setValue($f,$v){

		if(is_closure($v)){
			$v = $v($f);
		}

		$this -> value = $v;

	}
}
?>