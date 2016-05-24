<?php

namespace Item;

use CoreWine\DataBase\DB;
use CoreWine\Router;
use CoreWine\Request as Request;

use CoreWine\SourceManager\Controller as SourceController;

use Item\Repository;

abstract class Controller extends SourceController{


	/**
	 * Errors
	 */
	const SUCCESS_CODE = "success";
	const SUCCESS_ADD_MESSAGE = "Data was added with success";
	const SUCCESS_EDIT_MESSAGE = "Data was edited with success";
	const SUCCESS_DELETE_MESSAGE = "Data was removed with success";
	const ERROR_EXCEPTION_CODE = "exception";
	const ERROR_FIELDS_INVALID_CODE = "fields_invalid";
	const ERROR_FIELDS_INVALID_MESSAGE = "The values sent aren't valid";
	const ERROR_QUERY_RETRIEVING_ID = "id not retrieved";
	const ERROR_NOT_FOUND_CODE = "not_found";
	const ERROR_NOT_FOUND_MESSAGE = "Resource not found";

	/**
	 * Retrieve result as array
	 */
	const RESULT_ARRAY = 0;

	/**
	 * Retrieve results as object
	 */
	const RESULT_OBJECT = 1;

	/**
	 * Name of obj in url
	 */
	public $url;

	/**
	 * Item\Schema
	 */
	public $__schema = 'Item\Schema';

	/**
	 * Item\Repository
	 */
	public $__repository = 'Item\Repository';

	/**
	 * Item\Schema
	 */
	public $schema;

	/**
	 * Item\Repository
	 */
	public $repository;

	/**
	 * Routers
	 */
	public function __routes(){

		$url = $this -> url;

		$this -> route('all') -> url("/api/{$url}") -> get();
		$this -> route('add') -> url("/api/{$url}") -> post();
		$this -> route('get') -> url("/api/{$url}/{id}") -> get();
		$this -> route('edit') -> url("/api/{$url}/{id}") -> put();
		$this -> route('delete') -> url("/api/{$url}/{id}") -> delete();
	}

	/**
	 * Get api url
	 */
	public function getFullApiURL(){

		return Request::getDirUrl()."api/{$this -> url}";
	}

	/**
	 * Check
	 */
	public function __check(){
		$this -> schema = new $this -> __schema();
		$this -> repository = new $this -> __repository($this -> schema);
		$this -> __alterSchema();
	}

	/**
	 * Alter schema
	 */
	public function __alterSchema(){
		$this -> getRepository() -> __alterSchema();
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
	 * Get repository
	 *
	 * @return Repository
	 */
	public function getRepository(){
		return $this -> repository;
	}

	/**
	 * Get all the result
	 */
	public function all(){

		$results = $this -> __all(Controller::RESULT_ARRAY);
		return $this -> json($results);
	}

	/**
	 * Get all records
	 *
	 * @param int $type type of result (Array|Object)
	 * @return results
	 */
	public function __all($type){
		return $this -> getRepository() -> get($type);
	}


	/**
	 * Add new record
	 */
	public function add(){
		return $this -> json($this -> __add());
	}


	/**
	 * Add a new record
	 */
	public function __add(){


		$row = [];
		$raw = [];

		$fields = $this -> getSchema() -> getFields();

		$errors = []; 

		foreach($fields as $name => $field){

			if($field -> isAdd()){
				$raw[$field -> getName()] = Request::post($field -> getName());
			
				$row[$field -> getName()] = $field -> parseValueAdd($raw[$field -> getName()]);

				// Validate field
				$response = $field -> isValid($raw[$field -> getName()]);

				if(!$this -> isResponseSuccess($response)){
					$errors[$field -> getName()] = $response;
				}
			}
		}

		// Response status error if validation is failed
		if(!empty($errors)){
			$response = new \Item\Response\Error(self::ERROR_FIELDS_INVALID_CODE,self::ERROR_FIELDS_INVALID_MESSAGE);
			return $response -> setDetails($errors) -> setRequest($raw);
		}


		try{
			$id = $this -> getRepository() -> insert($row);

			if(!$id)
				throw new \Exception(self::ERROR_QUERY_RETRIEVING_ID);
			

		}catch(\Exception $e){

			$response = new \Item\Response\Error(self::ERROR_EXCEPTION_CODE,$e -> getMessage());
			return $response -> setRequest(Request::getCall());
		}


		$response = new \Item\Response\Success(self::SUCCESS_CODE,self::SUCCESS_ADD_MESSAGE);
		$result = $this -> __first($id[0],Controller::RESULT_ARRAY);
		return $response -> setData($result) -> setRequest(Request::getCall());



	}

	/**
	 * Retrieve a record
	 */
	public function get($id){
		$first = $this -> __first($id,Controller::RESULT_ARRAY);

		switch(Request::get('filter')){
			case 'edit':

			break;
			default:

			break;
		}

		return $this -> json($first);
	}

	/**
	 * Get a records
	 *
	 * @param int $id
	 * @param int $type type of result (Array|Object)
	 * @return results
	 */
	public function __first($id,$type){
		return $this -> getRepository() -> firstById($id,$type);
	}

	/**
	 * Edit a record
	 */
	public function edit($id){
		return $this -> json($this -> __edit($id,Controller::RESULT_ARRAY));
	}

	/**
	 * Get a records
	 *
	 * @param int $id
	 * @return results
	 */
	public function __edit($id){

		$result = $this -> __first($id,Controller::RESULT_ARRAY);

		if(!$result){

			$response = new \Item\Response\Error(self::ERROR_NOT_FOUND_CODE,self::ERROR_NOT_FOUND_MESSAGE);
			return $response -> setRequest(Request::getCall());

		}


		$row = [];
		$raw = [];

		$fields = $this -> getSchema() -> getFields();

		$errors = []; 

		foreach($fields as $name => $field){

			if($field -> isAdd()){
				$raw[$field -> getName()] = Request::put($field -> getName());
			
				$row[$field -> getName()] = $field -> parseValueEdit($raw[$field -> getName()]);

				// Validate field
				$response = $field -> isValid($raw[$field -> getName()]);

				if(!$this -> isResponseSuccess($response)){
					$errors[$field -> getName()] = $response;
				}
			}
		}

		// Response status error if validation is failed
		if(!empty($errors)){
			$response = new \Item\Response\Error(self::ERROR_FIELDS_INVALID_CODE,self::ERROR_FIELDS_INVALID_MESSAGE);
			return $response -> setDetails($errors) -> setRequest($raw);
		}


		try{
			$id = $this -> getRepository() -> update($id,$row);

			if(!$id)
				throw new \Exception(self::ERROR_QUERY_RETRIEVING_ID);
			

		}catch(\Exception $e){

			$response = new \Item\Response\Error(self::ERROR_EXCEPTION_CODE,$e -> getMessage());
			return $response -> setRequest(Request::getCall());
		}


		$response = new \Item\Response\Success(self::SUCCESS_CODE,self::SUCCESS_EDIT_MESSAGE);
		$result = $this -> __first($id[0],Controller::RESULT_ARRAY);
		return $response -> setData($result) -> setRequest(Request::getCall());

	}

	/**
	 * Delete a record
	 */
	public function delete($id){
		return $this -> json($this -> __delete($id));
	}

	/**
	 * Remove a new record
	 */
	public function __delete($id){



		$result = $this -> __first($id,Controller::RESULT_ARRAY);

		if(!$result){
			return $this -> getResponseNotFound();
		}

		try{


			$id = $this -> getRepository() -> deleteById($id);


		}catch(\Exception $e){

			$response = new \Item\Response\Error(self::ERROR_EXCEPTION_CODE,$e -> getMessage());
			return $response -> setRequest(Request::getCall());
		}

		$response = new \Item\Response\Success(self::SUCCESS_CODE,self::SUCCESS_DELETE_MESSAGE);
		return $response -> setData($result) -> setRequest(Request::getCall());



	}

	public function getResponseNotFound(){
		$response = new \Item\Response\Error(self::ERROR_NOT_FOUND_CODE,self::ERROR_NOT_FOUND_MESSAGE);
		return $response -> setRequest(Request::getCall());
	}

	public function isResponseSuccess(\Item\Response\Response $response){
		return ($response instanceof \Item\Response\Success);
	}

	public function isResponseError(\Item\Response\Response $response){
		return ($response instanceof \Item\Response\Erro);
	}

}


?>