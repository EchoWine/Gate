<?php

namespace Api;

use CoreWine\Http\Controller as SourceController;
use Api\Response;
use Api\Exceptions;
use CoreWine\Http\Request;
use Api\Service\Api;
use CoreWine\DataBase\DB;

abstract class Controller extends SourceController{

	/**
	 * Name of obj in url
	 *
	 * @var string
	 */
	public $url = null;

	/**
	 * ClassName ORM\Model
	 *
	 * @var string
	 */
	public $model = null;

	/**
	 * Defining routes
	 */
	public function __routes(){


		$url = $this -> url;

		$this -> route('all') -> url("/api/v1/{$url}") -> get();
		$this -> route('add') -> url("/api/v1/{$url}") -> post();
		$this -> route('copy') -> url("/api/v1/{$url}/{id}") -> post();
		$this -> route('get') -> url("/api/v1/{$url}/{id}") -> get();
		$this -> route('edit') -> url("/api/v1/{$url}/{id}") -> put();
		$this -> route('delete') -> url("/api/v1/{$url}/{id}") -> delete();

	}

	/**
	 * Get basic path api 
	 *
	 * @return string
	 */
	public function getApiURL(){

		return Api::url();
	}

	/**
	 * Get api url
	 *
	 * @return string
	 */
	public function getFullApiURL(){

		return Api::url()."{$this -> url}";
	}

	/**
	 * Check
	 */
	public function __check(){

		if($this -> getUrl() == null)
			throw new Exceptions\UrlNullException(static::class);

		if($this -> getModel() == null)
			throw new Exceptions\ModelNullException(static::class);

		else if(!class_exists($this -> getModel()))
			throw new Exceptions\ModelNotExistsException(static::class,$this -> getModel());

	
	}

	/**
	 * Get model
	 *
	 * @return string ClassName Model
	 */
	public function getModel(){
		return $this -> model;
	}

	/**
	 * Get url
	 *
	 * @return string
	 */
	public function getUrl(){
		return $this -> url;
	}

	/**
	 * Get Schema
	 *
	 * @return ORM\Schema
	 */
	public function getSchema(){
		return $this -> getModel()::schema();
	}

	/**
	 * Get Repository
	 *
	 * @return ORM\Repository
	 */
	public function getRepository($alias = null){
		return $this -> getModel()::repository($alias);
	}

	/**
	 * Get all records
	 *
	 * @return results
	 */
	public function all(Request $request){

		try{
			# Get repository alias _d0
			# This will prevent error with joins between same table
			$repository = $this -> getRepository('_d0');


			# Request
			$page = $request -> query -> get('page',1);
			$show = $request -> query -> get('show',100);
			$sort = $request -> query -> get('desc',null);
			$sort = $request -> query -> get('asc',$sort);
			$search = $request -> query -> get('search',[]);

			$direction = $sort == $request -> query -> get('desc') ? 'desc' : 'asc';

			# SORTING
			if($sort){
				
				$repository = $repository -> sortBy($sort,$direction);

			}else{

				$repository = $repository -> sortBy();
			}

			foreach((array)$search as $field => $params){

				$values = self::getArrayParams($params);

				$repository = $repository -> find($field,$values);
			
			}

			$repository = $repository -> paginate($show,$page);
			$repository = $repository -> select('_d0.*');

			$results = $repository -> get();

			return new Response\ApiAllSuccess([
				'results' => $results -> toArray(),
				'pagination' => $results -> getPagination() -> toArray()
			]);

		}catch(\Exception $e){

			# Return exception
			return new Response\ApiException($e);
		}

	}

	/**
	 * Get a records
	 *
	 * @param int $id
	 *
	 * @return results
	 */
	public function get(Request $request,$id){

	
		# Return error if not found
		if(!$model = $this -> getModel()::first($id))
			return new Response\ApiNotFound();

		switch($request -> query -> get('filter')){
			case 'edit':

			break;
			default:

			break;
		}

		return new Response\ApiGetSuccess($model);
	}

	/**
	 * Add a new record
	 *
	 * @return \Api\Response\Response
	 */
	public function add(Request $request){

		try{

			# Create and retrieve a new model
			$model = $this -> getModel()::create($request -> request -> all());

			# Get last validation
			$errors = $this -> getModel()::getLastValidate();

			# Return error if validation failed
			if(!empty($errors))
				return new Response\ApiFieldsInvalid($errors);

			# Return success
			return new Response\ApiAddSuccess($model);

		}catch(\Exception $e){

			# Return exception
			return new Response\ApiException($e);
		}

	}	
	
	/**
	 * Edit record
	 *
	 * @param int $id
	 *
	 * @return \Api\Response\Response
	 */
	public function edit(Request $request,$id){

		try{

			# Return error if not found
			if(!$model = $this -> getModel()::first($id))
				return new Response\ApiNotFound();

			# Get an "old model"
			$old_model = $model -> getClone();


			$model -> fill($request -> request -> all());
			$model -> save();


			# Get last validation
			$errors = $this -> getModel()::getLastValidate();

			# Return error if validation failed
			if(!empty($errors))
				return new Response\ApiFieldsInvalid($errors);

			return new Response\ApiEditSuccess($model,$old_model);

		}catch(\Exception $e){

			# Return exception
			return new Response\ApiException($e);
		}

	}

	/**
	 * Remove a new record
	 *
	 * @param mixed $id
	 *
	 * @return \Api\Response\Response
	 */
	public function delete(Request $request,$id){

		try{

			$models = [];

			foreach(self::getArrayParams($id) as $id){

				# Return error if not found
				if(!$model = $this -> getModel()::first($id))
					return new Response\ApiNotFound();

				# Delete
				$model -> delete();

				$models[] = $model;

			}
			
			# Return success
			return new Response\ApiDeleteSuccess($models);

		}catch(\Exception $e){

			# Return exception
			return new Response\ApiException($e);
		}


	}

	/**
	 * Copy a new record
	 *
	 * @param mixed $id
	 *
	 * @return \Api\Response\Response
	 */
	public function copy(Request $request,$id){

		try{

			$models = [];

			foreach(self::getArrayParams($id) as $id){

				# Return error if not found
				if(!$from_model = $this -> getModel()::first($id))
					return new Response\ApiNotFound();

				# Copy
				$new_model = $this -> getModel()::copy($from_model);


				$models[] = ['from' => $from_model,'new' => $new_model];

			}

			# Return success
			return new Response\ApiCopySuccess($models);

		}catch(\Exception $e){

			# Return exception
			return new Response\ApiException($e);
		}

	}

	public function getArrayParams($params){

		$params = preg_split('|(?<!\\\);|', $params);

		array_walk(
		    $params,
		    function(&$item){
		        $item = str_replace('\;', ';', $item);
		    }
		);

		if(!is_array($params))
			$params = [$params];

		return $params;

	}
}


?>