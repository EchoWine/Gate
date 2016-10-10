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
		$this -> route('get') -> url("/api/v1/{resource}/{source}/{id}") -> get();
		$this -> route('sync') -> url("/api/v1/{resource}/{id}") -> post();
		$this -> route('all') -> url("/api/v1/all") -> get();

		//$router -> get("/api/v1/{resource}/discovery/{key}","index")

	}

	/**
	 * Retrieve user given a token
	 *
	 * @param string $token
	 *
	 * @return User
	 */
	public function getUserByToken($token){
		if(!$token)
			return null;
		
		return User::where('token',$token) -> first();
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
	 * @Route get
	 *
	 * @return Response
	 */
	public function get(Request $request,$resource,$source,$id){
		
		if(!($user = $this -> getUserByToken($request -> query -> get('token')))){
			return $this -> json(['status' => 'error','message' => 'Token invalid']);
		}
		
		return $this -> json(WT::get(
			$user,
			$resource,
			$source,
			$id
		));
	}

	/**
	 * @Route sync
	 *
	 * @return Response
	 */
	public function sync(Request $request,$resource,$id){
		
		if(!($user = $this -> getUserByToken($request -> request -> get('token')))){
			return $this -> json(['status' => 'error','message' => 'Token invalid']);
		}
		
		return $this -> json(WT::sync(
			$user,
			$resource,
			$id
		));

	}

	/**
	 * @Route
	 *
	 * @return Response
	 */
	public function all(Request $request){
		
		if(!($user = $this -> getUserByToken($request -> query -> get('token')))){
			return $this -> json(['status' => 'error','message' => 'Token invalid']);
		}
		
		return $this -> json(WT::all(
			$user
		));

	}
}