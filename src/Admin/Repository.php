<?php

namespace Admin;

use CoreWine\DataBase\DB;

class Repository extends \Item\Repository{


	/**
	 * Get the QueryBuilder object
	 *
	 * @return CoreWine\DataBase\QueryBuilder
	 */
	public function table($type = null){

		$table = DB::table($this -> getSchema() -> getTable());
		


		if($type === null)
			return $table;


		$table = $this -> selectByViewGet($table);

		
		if($type === 0){
			return $table -> setParserResult(function($results){
				
				return $results;
			});
		}

		if($type === 1){
			return $table -> setParserResult(function($results){
				
				foreach($results as $n => $result){
					$results[$n] = $this -> schema -> parseResult($result);
				}

				return $results;
			});
		}

	}

	public function selectByViewGet($table){

		foreach($this -> getSchema() -> getFields() as $field){
			if($field -> isViewGet()){
				$table = $table -> select($field -> getColumn());
			}
		}

		return $table;

	}

}

?>