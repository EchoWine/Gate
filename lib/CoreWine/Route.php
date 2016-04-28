<?php

namespace CoreWine;

class Route{

	/**
	 * Current route
	 */
	public static $route = null;

	/**
	 * List of all routes
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

	/**
	 * Set get route
	 *
	 * @param string $url url
	 * @param callback|array $callback callback or array of info
	 */
	public static function get($url,$callback){

		$current_url = self::getRelativeUrl();

		$alias = $url;
		$where = [];

		if(is_array($callback)){

			if(isset($callback['as']))
				$alias = $callback['as'];

			if(isset($callback['where']))
				$where = $callback['where'];

			if(isset($callback['callback']))
				$callback = $callback['callback'];

			if(is_string($callback)){
				$class = debug_backtrace()[1]['class'];
				$callback = function() use($class,$callback){ return $class::$callback(); };
			}


		}

		$regex_url = self::parseUrlToRegex($url,$where);


		# Remove last /
		/*	
		if($current_url[strlen($current_url)-1] == "/")
			$current_url = substr($current_url,0,strlen($current_url)-2);
			*/


		if(preg_match($regex_url,$current_url,$res)){
			unset($res[0]);
			foreach($res as &$k)
				$k = preg_replace("/^\/(.*)$/","$1",$k);

			self::$route = (object)[
				'url' => $url,
				'regex_url' => $regex_url,
				'callback' => $callback,
				'param' => $res,
				'alias' => $alias
			];
		}

		self::$routes[$alias] = (object)[
			'url' => $url,
			'regex_url' => $regex_url,
			'callback' => $callback,
		];


	}

	/**
	 * Get url from route
	 *
	 * @param string $alias url
	 */
	public static function url($alias){

		$params = func_get_args();

		$url = self::$routes[$alias] -> url;

		preg_match_all("/\{([^}]*)\}/",$url,$matches);

		$i = 1;
		foreach($matches[1] as $match){
			if(isset($params[$i]))
				$url = str_replace("{".$match."}",$params[$i++],$url);
			else{

				# Match only if optional
				if(!preg_match("/^(.*)\?$/",$match)){
					throw new \InvalidArgumentException();
				}
			}
		}

		$url = substr($url,1);


		$url = preg_replace("/\/\{([^}\?]*)\?\}/","",$url);
		return self::getDirUrl().$url;
		
	}

	/**
	 * Return true if current route
	 *
	 * @param bool current route
	 */
	public static function is($alias){
		return self::$route !== null && self::$route -> alias == $alias;
	}

	/**
	 * Add data to a route
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
			$data = array_merge(self::$global_data,$data,($c = self::getDataByCurrentRoute()) !== null ? $c : []);

	
		foreach((array)$data as $n => $k)
			$GLOBALS[$n] = $k;

	}

	/**
	 * Get data by current route
	 *
	 * @return array
	 */
	public static function getDataByCurrentRoute(){
		return isset(self::$data[self::$route -> alias]) ? self::$data[self::$route -> alias] : null;
	}

	/**
	 * Parse url to regex
	 *
	 * @param string $url to parse
	 * @param array $where
	 * @return string url parsed
	 */
	public static function parseUrlToRegex($url,$where){

		foreach($where as $n => $k){
			if(is_array($k)) $k = implode("|",$k);

			$url = preg_replace("/\/\{(".$n.")+(\?)?\}/","(/$k)$2",$url);
		}

		/*
		preg_match_all("/\{([^}]*)\}/",$url,$r);
		foreach($r[1] as $k){
			echo $k."\n";
		}
		*/

		$url = preg_replace("/\/\{([^}\?]*)\?\}/","(/[^/]*)?",$url);
		$url = preg_replace("/\{([^}]*)\}/","([^/]*)",$url);
		$url = preg_replace("/\//","\/",$url);
		$url = "/^".$url."$/";

		return $url;
	}

	/**
	 * Load current route
	 *
	 * @return mixed result of callback
	 */
	public static function load(){

		return self::$route != null
			? call_user_func_array(self::$route -> callback,self::$route -> param)
			: die('No Routes found');
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
	 * @return string relative url
	 */
	public static function getDirUrl(){
		return dirname($_SERVER['PHP_SELF'])."/";
	}

}

?>