<?php

namespace CoreWine;

class Router{

	/**
	 * Current Router
	 */
	public static $route = null;

	/**
	 * List of all Routers
	 */
	public static $routes = [];

	/**
	 * Data
	 */
	public static $data = [];

	/**
	 * Global Data
	 */
	public static $global_data = [];

	public static function route(){
		return self::$routes[] = new Route();
	}
	public static function any(){
		return self::route();
	}

	public static function get(){
		return self::route() -> get();
	}

	public static function post(){
		return self::route() -> post();
	}

	public static function put(){
		return self::route() -> put();
	}

	public static function delete(){
		return self::route() -> delete();
	}

	public static function request(){

		foreach(self::$routes as $route){

			$current_url = self::getRelativeUrl();

			if($route -> checkMethod(Request::getMethod())){
				if($route -> checkUrl($current_url)){
					return $route;
				}

			}

		}
	}

	/**
	 * Return a Router with given alias
	 *
	 * @param string $alias
	 * @return Router
	 */
	public static function getRouterByAlias($alias){
		foreach(self::$routes as $route){
			if($route -> alias == $alias)
				return $route;
		}

		return null;
	}

	/**
	 * Get url from Router
	 *
	 * @param string $alias url
	 */
	public static function url($alias){

		$params = func_get_args();

		$url = self::getRouterByAlias($alias);

		if($url == null)
			throw new Exceptions\RouteException("No Route found for alias: ". $alias);

		return $url -> getFullUrl($params);
		
	}

	/**
	 * Return true if current Router
	 *
	 * @param bool current Router
	 */
	public static function is($alias){
		return self::$route !== null && self::$route -> alias == $alias;
	}

	/**
	 * Return current Router
	 *
	 * @param bool current Router
	 */
	public static function active(){
		return self::getDirUrl(''). self::$route -> full_url;
	}

	/**
	 * Add data to a Router
	 *
	 * @param array $route
	 * @param array $data
	 */
	public static function add($route,$data){

		self::$data[$route] = empty(self::$data[$route])
			? $data
			: array_merge(self::$data[$route],$data);
	}

	/**
	 * Add data global
	 *
	 * @param array $data
	 */
	public static function global($data){
		self::$global_data = array_merge(self::$global_data,$data);
	}

	/**
	 * Set data to global scope
	 *
	 * @param string $alias url
	 * @param array $data
	 */
	public static function view($data = []){

		if(self::$route !== null)
			$data = array_merge(self::$global_data,$data,($c = self::getDataByCurrentRouter()) !== null ? $c : []);

	
		foreach((array)$data as $n => $k)
			$GLOBALS[$n] = $k;

	}

	/**
	 * Get data by current Router
	 *
	 * @return array
	 */
	public static function getDataByCurrentRouter(){
		return isset(self::$data[self::$route -> alias]) ? self::$data[self::$route -> alias] : null;
	}

	/**
	 * Set current route
	 */
	public static function setRequest(){
		self::$route = self::request();
	}

	/**
	 * Load current Router
	 *
	 * @return mixed result of callback
	 */
	public static function load(){
		

		if(self::$route == null)
			throw new Exceptions\RouteException("No Route found for: ". self::getRelativeUrl());

		return call_user_func_array(self::$route -> callback,self::$route -> param);
	}

	/**
	 * Get relative url
	 * 
	 * @return string relative url
	 */
	public static function getRelativeUrl(){
		return preg_replace("/(\?|&).*/",'',str_replace(dirname($_SERVER['PHP_SELF']),'',$_SERVER['REQUEST_URI']));
	}

	/**
	 * Get relative url
	 * 
	 * @param string add path
	 * @return string relative url
	 */
	public static function getDirUrl($path = '/'){
		return dirname($_SERVER['PHP_SELF']).$path;
	}

}

?>