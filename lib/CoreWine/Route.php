<?php

namespace CoreWine;

class Route{

	public $url;

	public $url_regex;

	public $url_full;

	public $method;

	public $param;
	
	public $alias;

	public $where;

	public $callback;

	public $middleware;

	public function __construct($url_regex = null){
		$this -> url_regex = $url_regex;
	}

	public function as($as){
		return $this -> alias($as);
	}

	public function url($url){
		$this -> url = $url;
		return $this;
	}

	public function url_regex($url){
		$this -> url_regex = $url;
		return $this;
	}

	public function url_full($url){
		$this -> url_full = $url;
		return $this;
	}

	public function method($method){
		$this -> method = $method;
		return $this;
	}

	public function param($param){
		$this -> param = $param;
		return $this;
	}

	public function alias($alias){
		$this -> alias = $alias;
		return $this;
	}

	public function where($where){
		$this -> where = $where;
		return $this;
	}

	public function callback($callback){
		$this -> callback = $callback;
		return $this;
	}

	public function middleware($middleware){
		$this -> middleware= $middleware;
		return $this;
	}

	public function any(){
		$this -> method = null;
		return $this;
	}

	public function get(){
		$this -> method = Request::METHOD_GET;
		return $this;
	}

	public function post(){
		$this -> method = Request::METHOD_POST;
		return $this;
	}

	public function put(){
		$this -> method = Request::METHOD_PUT;
		return $this;
	}

	public function delete(){
		$this -> method = Request::METHOD_DELETE;
		return $this;
	}

	public function checkMethod($method){
		return $this -> method == null ? true : $this -> method == $method;
	}

	public function checkUrl($url){
		$regex_url = self::parseUrlToRegex($this -> url,$this -> where);
		$this -> url_regex($regex_url);

		if(preg_match($regex_url,$url,$res)){

			// ? Remove first slash ??
			unset($res[0]);

			foreach($res as &$k){
				$k = preg_replace("/^\/(.*)$/","$1",$k);
			}

			$this -> param($res);

			return true;
		}

		return false;


	}

	public function getFullUrl($params){

		$url = $this -> url;

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
		return Router::getDirUrl().$url;
	}

	/**
	 * Parse url to regex
	 *
	 * @param string $url to parse
	 * @param array $where
	 * @return string url parsed
	 */
	public static function parseUrlToRegex($url,$where){

		if($where){
			foreach($where as $n => $k){
				if(is_array($k)) $k = implode("|",$k);

				$url = preg_replace("/\/\{(".$n.")+(\?)?\}/","(/$k)$2",$url);
			}
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

}

?>