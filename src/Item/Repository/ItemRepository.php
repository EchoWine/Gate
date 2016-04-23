<?php

namespace Item\Repository;

use CoreWine\DataBase\DB;

class ItemRepository{

	public $schema;

	public function __construct($schema){
		$this -> schema = $schema;
	}


	public function alterSchema(){

		$fields = $this -> schema -> getFields();
		DB::schema($this -> schema -> getTable(),function($table) use ($fields){
			foreach($fields as $name => $field){
				$field -> alter($table);
			}
		});
	}

}

?>