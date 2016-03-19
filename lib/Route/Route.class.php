<?php
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
	 * Set get route
	 *
	 * @param string $url url
	 * @param callback|array $callback callback or array of info
	 */
	public static function get($url,$callback){

		$current_url = http::getRelativeUrl();

		$alias = $url;
		$where = [];

		if(is_array($callback)){

			if(isset($callback['as']))
				$alias = $callback['as'];

			if(isset($callback['where']))
				$where = $callback['where'];

			if(isset($callback['callback']))
				$callback = $callback['callback'];



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
				'param' => $res
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
	public static function is($alias){

		$params = func_get_args();

		$url = self::$routes[$alias] -> url;

		preg_match_all("/\{([^}]*)\}/",$url,$matches);

		$i = 1;
		foreach($matches[1] as $match){
			if(isset($params[$i]))
				$url = str_replace("{".$match."}",$params[$i++],$url);
		}

		$url = substr($url,1);


		$url = preg_replace("/\/\{([^}\?]*)\?\}/","",$url);
		return http::getDirUrl().$url;
		
	}


	/**
	 * Get callback from route
	 *
	 * @param string $alias url
	 * @param array $data
	 */
	public static function view($alias,$data = []){

		foreach((array)$data as $n => $k)
			$GLOBALS[$n] = $k;
		

		return self::load(self::$routes[$alias]);
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
			$url = preg_replace("/\{".$n."\}/","($k)",$url);
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
	 * @param object $route route
	 * @return mixed result of callback
	 */
	public static function load($route = null){

		if($route == null)
			$route = self::$route;

		return $route != null
			? call_user_func_array($route -> callback,self::$route -> param)
			: die('No Routes found');
	}

}

?>