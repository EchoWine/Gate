<?php

namespace Serie\Controller\Api;

use CoreWine\Http\Controller as BasicController;
use Api\Response;
use Api\Exceptions;
use Serie\Service\Serie;
use Request;
use Auth\Model\User;

class SearchController extends BasicController{


	/**
	 * Defining routes
	 */
	public function __routes(){
		$this -> route('discovery') -> url("/api/v1/{resource}/discovery/{key}") -> get();
		$this -> route('add') -> url("/api/v1/{resource}/add") -> post();

		//$router -> get("/api/v1/{resource}/discovery/{key}","index")

	}

	/**
	 * @Route Index
	 *
	 * @return Response
	 */
	public function discovery(Request $request,$resource,$key){
		if(!($user = $this -> getUserByToken($request -> query -> get('token')))){
			return $this -> json(['error' => 'token not correct']);
		}

		return $this -> json(Serie::discovery($user,$resource,$key));
	}

	/**
	 * @Route Add
	 *
	 * @return Response
	 */
	public function add(Request $request,$resource){
		if(!($user = $this -> getUserByToken($request -> request -> get('token')))){
			return $this -> json(['error' => 'token not correct']);
		}

		return $this -> json(Serie::add(
			$user,
			$resource,
			$request -> request -> get('source'),
			$request -> request -> get('id'))
		);
	}

	public function getUserByToken($token){
		return User::where('token',$token) -> first();
	}
}