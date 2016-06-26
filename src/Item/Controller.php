<?php

namespace Item;

use CoreWine\DataBase\DB;
use CoreWine\Router;
use CoreWine\Request as Request;

use CoreWine\SourceManager\Controller as SourceController;

use Item\Repository;
use Item\Response as Response;

abstract class Controller extends SourceController{

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
	 * Item\Entity
	 */
	public $__entity = 'Item\Entity';

	/**
	 * Routers
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
	 */
	public function getFullApiURL(){

		return Request::getDirUrl()."api/{$this -> url}";
	}

	/**
	 * Check
	 */
	public function __check(){

	}

	/**
	 * Get entity
	 *
	 * @return Entity
	 */
	public function getEntity(){
		return $this -> __entity;
	}

	/**
	 * Get all the result
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

			$results = $repository -> get();

			return new Response\ApiAllSuccess([
				'results' => $results,
				'count' => $pagination -> count,
				'page' => $pagination -> page,
				'pages' => $pagination -> pages,
				'from' => $pagination -> from,
				'to' => $pagination -> to
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
	 * @return results
	 */
	public function __first($id){
		return $this -> getRepository() -> where('id',$id) -> first();
	}

	/**
	 * Add a new record
	 *
	 * @return \Item\Response\Response
	 */
	public function __add(){

		try{

			$errors = $this -> getEntity()::validateCreate(Request::all());

			if(!empty($errors))
				return new Response\ApiFieldsInvalid($errors);

			$entity = $this -> getEntity()::create(Request::all());


			return new Response\ApiAddSuccess($entity -> id,$entity);

		}catch(\Exception $e){

			return new Response\ApiException($e);
		}

	}	
	
	/**
	 * Edit record
	 *
	 * @param int $id
	 * @return \Item\Response\Response
	 */
	public function __edit($id){

		try{

			if(!$result = $this -> __first($id))
				return new Response\ApiNotFound();
				
			$repository = $this -> getRepository();

			$errors = $this -> __editFields($repository,$id,$result);

			if(!empty($errors))
				return new Response\ApiFieldsInvalid($errors);

			$result = $repository -> where('id',$id) -> update();

			if(empty($result))
				return new Response\ApiEditError();
			
			return new Response\ApiEditSuccess($id,$result,$this -> __first($id));

		}catch(\Exception $e){

			return new Response\ApiException($e);
		}

	}

	/**
	 * Remove a new record
	 */
	public function __delete($id){

		$result = $this -> __first($id);

		if(!$result)
			return new Response\ApiNotFound();
		
		$repository = $this -> getRepository();

		$this -> __deleteFields($repository,$result);

		$id = $repository -> where('id',$id) -> delete();
	
		return new Response\ApiDeleteSuccess($id,$result);

	}

	/**
	 * Copy a new record
	 */
	public function __copy($id){

		$result = $this -> __first($id);

		if(!$result)
			return new Response\ApiNotFound();
		
		$repository = $this -> getRepository();

		$this -> __copyFields($repository,$result);

		$id = $repository -> insert();

		$resource = $this -> __first($id[0]);

		return new Response\ApiCopySuccess($id,$result,$resource);

	}

	/**
	 * Retrieve value of fields to update and relative errors
	 *
	 * @param int $id
	 * @param array $result
	 * @return array
	 */
	public function __editFields(&$repository,$id,$result){

		$errors = [];
		$fields = $this -> getSchema() -> getFields();

		foreach($fields as $name => $field){

			if($field -> isEdit()){

				$name = $field -> getName();
				$col = $field -> getColumn();
				$value = Request::put($name);

				if($field -> isEditNeeded($value)){
				
					$field -> edit($repository,$value);

					$response = $field -> isValid($value);

					if(!$this -> isResponseSuccess($response)){
						$errors[$name] = $response;
					}

					if($this -> isResponseSuccess($response)){
						if($field -> isUnique()){
							if($this -> getRepository() -> where('id','!=',$id) -> where($col,$value) -> count()){
								$errors[$name] = new Response\ApiFieldErrorNotUnique($field -> getLabel(),$value);
							
							}
						}
					}
				}
			}
		}

		return $errors;
	}

	/**
	 * Retrieve value of fields to copy and relative errors
	 *
	 * @return array
	 */
	public function __copyFields($repository,$result){

		$fields = $this -> getSchema() -> getFields();

		foreach($fields as $name => $field){

			if($field -> isCopy()){

				$col = $field -> getColumn();
				$value = $result[$col];

				if($field -> isUnique()){
					$n = 0;
					do{
						$value_copied = $field -> parseValueCopy($value,$n++);
						$exists = $this -> getRepository() -> exists([$col => $value_copied]);
					}while($exists);
					$value = $value_copied;
				}

				$row[$field -> getName()] = $value;

			}
		}

	}

	/**
	 * Retrieve value of fields to copy and relative errors
	 *
	 * @param $repository
	 * @param $result
	 *
	 * @return array
	 */
	public function __deleteFields($repository,$result){

		$fields = $this -> getSchema() -> getFields();

		foreach($fields as $name => $field){

			
		}

	}


}


?>