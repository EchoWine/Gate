<?php

namespace Item;

class Entity{

	public $schema;

	public $fields;

	public function __construct(Schema $schema){
		$this -> schema = $schema;
	}

	public function setFieldsByArray($result){

		$return = [];
		foreach($this -> schema -> getFields() as $fieldSchema){
			$value = $result[$fieldSchema -> getColumn()];
			$entity = $fieldSchema -> newEntity($value);
			$this -> fields[$fieldSchema -> getName()] = $entity;
			$this -> {$fieldSchema -> getName()} = $entity;
		}

		return $this;
	}

}

?>
