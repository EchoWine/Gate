<?php

class stdData extends stdObject{

	public $label;

	public $name;

	public $value;

	public function __construct($f = null,$v = null,$l = null){

		$this -> setLabel($l);
		$this -> setName($f);
		$this -> setValue($f,$v);
	}

	public function setLabel($v = 'Default Label'){
		$this -> label = $v;
	}


	public function setName($v = ''){
		$this -> name = $v;
	}

	public function setValue($f,$v){
		

		if(is_closure($v)){
			$v = $v($f);
		}

		$this -> value = $v;

	}
}
?>