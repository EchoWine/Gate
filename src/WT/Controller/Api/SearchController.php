<?php

namespace WT\Controller\Api;

use CoreWine\Http\Controller as BasicController;
use Api\Response;
use Api\Exceptions;
use WT\Service\WT;
use Request;
use Auth\Model\User;

class SearchController extends BasicController{


	/**
	 * Defining routes
	 */
	public function __routes(){
		$this -> route('discovery') -> url("/api/v1/{resource}/discovery/{key}") -> get();
		$this -> route('add') -> url("/api/v1/{resource}/add") -> post();
		$this -> route('remove') -> url("/api/v1/{resource}/remove") -> post();

		//$router -> get("/api/v1/{resource}/discovery/{key}","index")

	}

	/**
	 * @Route Index
	 *
	 * @return Response
	 */
	public function discovery(Request $request,$resource,$key){
		if(!($user = $this -> getUserByToken($request -> query -> get('token')))){
			return $this -> json(['status' => 'error','message' => 'Token invalid']);
		}

		return $this -> json(WT::discovery($user,$resource,$key));
	}

	/**
	 * @Route Add
	 *
	 * @return Response
	 */
	public function add(Request $request,$resource){
		if(!($user = $this -> getUserByToken($request -> request -> get('token')))){
			return $this -> json(['status' => 'error','message' => 'Token invalid']);
		}
		
		return $this -> json(WT::add(
			$user,
			$resource,
			$request -> request -> get('source'),
			$request -> request -> get('id'))
		);
	}

	/**
	 * @Route delete
	 *
	 * @return Response
	 */
	public function remove(Request $request,$resource){
		if(!($user = $this -> getUserByToken($request -> request -> get('token')))){
			return $this -> json(['status' => 'error','message' => 'Token invalid']);
		}
		
		
		return $this -> json(WT::delete(
			$user,
			$resource,
			$request -> request -> get('source'),
			$request -> request -> get('id'))
		);
	}

	/**
	 * Retrieve user given a token
	 *
	 * @param string $token
	 *
	 * @return User
	 */
	public function getUserByToken($token){
		return User::where('token',$token) -> first();
	}
}