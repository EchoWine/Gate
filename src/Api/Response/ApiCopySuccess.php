<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiCopySuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'sucess';

	/**
	 * Message
	 */
	const MESSAGE = "Resource was copied with success";

	/**
	 * Construct
	 *
	 * @param ORM\Model $new_model
	 * @param ORM\Model $from_model
	 */
	public function __construct($models){

		$data = [];

		foreach($models as $model){
			$data[$model['new'] -> id] = $model['new'] -> toArray();
		}

		$ids = implode(", ",array_map(function($model){
			return "#".$model['from'] -> id." => #".$model['new'] -> id;
		},$models));

		$message = "Copied: {$ids}";

		parent::__construct(static::CODE,$message);

		$this -> setData($data) -> setRequest(Request::getCall());

	}
}

?>