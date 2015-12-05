<?php
class Model{
  	
  	private $fields;

	public function setField($a){
		$this -> fields[$a -> name] = $a;
	}

	public function setFields(array $a){
		foreach($a as $k){
			$this -> fields[$k -> name] = $k;
		}
	}

}
?>