<?php

namespace Api;

use CoreWine\DataBase\DB;
use CoreWine\Router;
use CoreWine\Request as Request;

use CoreWine\SourceManager\Controller as SourceController;

use Api\Repository;
use Api\Response as Response;

abstract class Controller extends SourceController{

	/**
	 * Name of obj in url
	 *
	 * @var string
	 */
	public $url;

	/**
	 * ClassName ORM\Model
	 *
	 * @var string
	 */
	public $model = 'Item\Model';

	/**
	 * Defining routes
	 */
	public function __routes(){

		$url = $this -> url;

		$this -> route('all') -> url("/api/{$url}") -> get();
		$this -> route('add') -> url("/api/{$url}") -> post();
		$this -> route('copy') -> url("/api/{$url}/{id}") -> post();
		$this -> route('get') -> url("/api/{$url}/{id}") -> get();
		$this -> route('edit') -> url("/api/{$url}/{id}") -> put();
		$this -> route('delete') -> url("/api/{$url}/{id}") -> delete();
	}

	/**
	 * Get api url
	 *
	 * @return string
	 */
	public function getFullApiURL(){

		return Request::getDirUrl()."api/{$this -> url}";
	}

	/**
	 * Check
	 */
	public function __check(){}

	/**
	 * Get model
	 *
	 * @return string ClassName Model
	 */
	public function getModel(){
		return $this -> model;
	}

	/**
	 * Get Schema
	 *
	 * @return ORM\Schema
	 */
	public function getSchema(){
		$model = $this -> getModel();
		return $model::schema();
	}

	/**
	 * Get Repository
	 *
	 * @return ORM\Repository
	 */
	public function getRepository(){
		$model = $this -> getModel();
		return $model::repository();
	}

	/**
	 * Get all the result
	 *
	 * @return Response;
	 */
	public function all(){
		return $this -> json($this -> __all());
	}

	/**
	 * Retrieve a record
	 */
	public function get($id){

		$first = $this -> __first($id);

		switch(Request::get('filter')){
			case 'edit':

			break;
			default:

			break;
		}


		$response = new Response\ApiGetSuccess($id,$first);

		return $this -> json($response);
	}

	/**
	 * Add new record
	 */
	public function add(){
		return $this -> json($this -> __add());
	}

	/**
	 * Edit a record
	 */
	public function edit($id){
		return $this -> json($this -> __edit($id));
	}

	/**
	 * Delete a record
	 */
	public function delete($id){
		return $this -> json($this -> __delete($id));
	}


	/**
	 * Copy a record
	 */
	public function copy($id){
		return $this -> json($this -> __copy($id));
	}

	/**
	 * Get all records
	 *
	 * @return results
	 */
	public function __all(){

		try{
			
			$repository = $this -> getRepository();

			$sort = $this -> __allSort($repository);

			$pagination = $this -> __allPagination($repository);

			$results = $repository -> get() -> toArray();
			
			return new Response\ApiAllSuccess([
				'results' => $results,
				'pagination' => $pagination
			]);

		}catch(\Exception $e){

			return new Response\ApiException($e);
		}
	}

	/**
	 * Get sort information
	 *
	 * @param Repository $repository
	 *
	 * @return Sort Part
	 */
	public function __allSort(&$repository){
		$sort = Request::get('desc',null);
		$sort = Request::get('asc',$sort);
		$direction = $sort == Request::get('desc') ? 'desc' : 'asc';

		# SORTING
		if($sort){

			# If the not exists the field
			if(!$this -> getSchema() -> hasField($sort))
				return Response\ApiAllErrorParamSortNotExists();
			

			$field = $this -> getSchema() -> getField($sort);

			# If the field isn't enabled to sorting
			if(!$field -> isSort())
				return Response\ApiAllErrorParamSortNotValid();
			

			$repository = $repository -> orderBy($field -> getColumn(),$direction);

		}else{

			$repository = $repository -> orderBy($this -> getSchema() -> getSortDefaultField() -> getColumn(),$this -> getSchema() -> getSortDefaultDirection());
		}
	}

	/**
	 * Pagination
	 *
	 * @param Repository $repository
	 *
	 * @return Pagination Part
	 */
	public function __allPagination(&$repository){

		# COUNT ALL THE RESULTS
		$count = $repository -> count();

		$show = $this -> __allShow($repository);

		# GET PAGES
		$pages = ceil($count / $show);

		# PAGINATION
		$page = Request::get('page',1);

		if($page !== 1){

			if($page > $pages)
				$page = $pages;

			if($page <= 0){
				
				return Response\ApiAllErrorParamPage();
			}

			$skip = ($page - 1) * $show;

			$repository = $repository -> skip($skip);
		}else{
			$skip = 0;
		}

		return (object)[
			'count' => $count,
			'page' => $page,
			'pages' => $pages,
			'from' => $skip + 1,
			'to' => $skip + $count,
		];
	}

	public function __allShow(&$repository){

		$show = Request::get('show',null);
		if($show){

			if($show <= 0){
				
				return new Response\ApiAllErrorParamShow();
			}

			$repository = $repository -> take($show);

		}else{
			$show = 100;
		}

		return $show;
	}

	/**
	 * Get a records
	 *
	 * @param int $id
	 *
	 * @return results
	 */
	public function __first($id){
		if(!$model = $this -> getModel()::where('id',$id) -> first())
			return new Response\ApiNotFound();

		return $model;
	}

	/**
	 * Add a new record
	 *
	 * @return \Api\Response\Response
	 */
	public function __add(){

		try{

			# Create and retrieve a new model
			$model = $this -> getModel()::create(Request::all());

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
	public function __edit($id){

		try{

			# Return error if not found
			if(!$model = $this -> getModel()::firstByPrimary($id))
				return new Response\ApiNotFound();

			# Get last validation
			$errors = $this -> getModel()::getLastValidate();

			# Return error if validation failed
			if(!empty($errors))
				return new Response\ApiFieldsInvalid($errors);

			# Get an "old model"
			$old_model = clone $model;

			$model -> fill(Request::all());
			$model -> save();

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
	public function __delete($id){

		try{

			# Return error if not found
			if(!$from_model = $this -> getModel()::firstByPrimary($id))
				return new Response\ApiNotFound();

			# Delete
			$model -> delete();
			
			# Return success
			return new Response\ApiDeleteSuccess($model);

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
	public function __copy($id){

		try{

			# Return error if not found
			if(!$from_model = $this -> getModel()::firstByPrimary($id))
				return new Response\ApiNotFound();

			# Copy
			$new_model = $this -> getModel()::copy($from_model);

			# Return success
			return new Response\ApiCopySuccess($new_model,$from_model);

		}catch(\Exception $e){

			# Return exception
			return new Response\ApiException($e);
		}

	}


}


?>