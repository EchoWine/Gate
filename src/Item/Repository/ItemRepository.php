<?php

namespace Item\Repository;

use CoreWine\DB as DB;

class ItemRepository{

	public $itemSchema;

	public function __construct($itemSchema){
		$this -> itemSchema = $itemSchema;
	}

	public function getItemSchema(){
		return $this -> itemSchema;
	}

	public function alterSchema(){
		$itemSchema = $this -> getItemSchema();
		$fields = $itemSchema::$fields;

		DB::schema($itemSchema::$table,function($table) use ($fields){
			foreach($fields as $name => $field){
				$table -> string($name);
			}
		});
	}

}

?>