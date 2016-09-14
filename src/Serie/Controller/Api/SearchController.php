<?php

namespace Serie\Controller\Api;

use CoreWine\Http\Controller as BasicController;
use Api\Response;
use Api\Exceptions;
use Serie\Service\Serie;
use Request;

class SearchController extends BasicController{


	/**
	 * Defining routes
	 */
	public function __routes(){
		$this -> route('index') -> url("/api/v1/{resource}/discovery/{key}") -> get();

	}

	/**
	 * @Route Index
	 *
	 * @return Response
	 */
	public function index(Request $request,$resource,$key){
		return $this -> json(Serie::discovery($resource,$key));
	}
}