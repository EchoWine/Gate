<?php

namespace Admin;

use CoreWine\DataBase\DB;

class Repository extends \Item\Repository{

	public function get(){
		$this -> selectByViewGet();
		return parent::get();
	}

	public function selectByViewGet(){

		foreach($this -> getSchema() -> getFields() as $field){
			if($field -> isViewGet()){
				$this -> select($field -> getColumn());
			}
		}

		return $this;

	}

}

?>