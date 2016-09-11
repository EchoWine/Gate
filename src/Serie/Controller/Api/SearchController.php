<?php

namespace Serie\Controller\Api;

use CoreWine\SourceManager\Controller as BasicController;
use Api\Response;
use Api\Exceptions;
use CoreWine\Http\Request;
use Serie\Service\Serie;

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
	public function index($resource,$key){
		return $this -> json(Serie::discovery($resource,$key));
	}
}