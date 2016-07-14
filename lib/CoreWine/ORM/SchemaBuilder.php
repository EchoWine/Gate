<?php

namespace CoreWine\ORM;

use CoreWine\ORM\Field\Field\Schema as FieldSchema;

class SchemaBuilder{

	/**
	 * List fields
	 */
	public static $fields;

	/**
	 * Set fields
	 *
	 * @param array $fields
	 */
	public static function setFields($fields){
		self::$fields = $fields;
	}

	/**
	 * Construct
	 *
	 * @param Schema $schema
	 */
	public function __construct($schema){
		$this -> schema = $schema;
	}

	/**
	 * Get schema
	 *
	 * @return Schema
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Return if class field exist by name
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isField($name){
		return isset(self::$fields[$name]);
	}

	/**
	 * Get class field by name
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function getField($name){
		return self::$fields[$name];
	}

	/**
	 * Add a field
	 *
	 * @param $class
	 * @param $name
	 */
	public function field($class,$name){
		if(is_subclass_of($class,FieldSchema::class)){
			$arguments = func_get_args();
			unset($arguments[0]);
			$field = call_user_func_array($class.'::factory', $arguments);
			$field -> addToModelSchema($this -> getSchema());
			return $field;
		}else{
			throw new \Exception('Error during creation of field '.$class);
		}
	}


	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function __call($method, $arguments){

		if($this -> isField($method)){
			$class = $this -> getField($method);
			if(is_subclass_of($class,FieldSchema::class)){


				$field = call_user_func_array($class.'::factory', $arguments);
				$field -> addToModelSchema($this -> getSchema());
				return $field;

			}else{
				throw new \Exception('Error during creation of field '.$class);
			}
		}
		

		throw new \Exception("Fatal error: Call to undefined method Model::{$method}()");
		
	}

}
?>