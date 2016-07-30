<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiDeleteSuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'success';

	/**
	 * Construct
	 *
	 * @param ORM\Model $model
	 */
	public function __construct($models){

		$data = [];

		foreach($models as $model){
			$data[$model -> id] = $model -> toArray();
		}

		$ids = implode(", ",array_map(function($value){
			return "#".$value;
		},array_keys($data)));

		$message = "Deleted: {$ids}";

		parent::__construct(static::CODE,$message);


		$this -> setData($data) -> setRequest(Request::getCall());

	}
}

?>