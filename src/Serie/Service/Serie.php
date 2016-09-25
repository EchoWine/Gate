<?php

namespace Serie\Service;
use Serie\Api as Api;

class Serie{

	public static $sources = [
		Api\TheTVDB::class,
	];

	/**
	 * Update the database
	 */
	public static function update(){

	}

	/**
	 * Discovery new resources
	 *
	 * @param string $resource
	 * @param string $key
	 *
	 * @return array
	 */
	public static function discovery($resource,$key){

		$response = [];
			
		foreach(self::$sources as $source){

			$source = new $source();

			if($source -> isResource($resource))
				$response[$source -> getName()] = $source -> discovery($key);

		}

		return $response;
	}

	/**
	 * Add a new resource
	 *
	 * @param string $resource
	 * @param string $source_name
	 * @param mixed $id
	 *
	 * @return array
	 */
	public static function add($resource,$source_name,$id){

		$response = [];
			
		foreach(self::$sources as $source){

			$source = new $source();

			if($source -> getName() == $source_name)
				$response = $source -> add($id);

		}

		return $response;
	}
}

?>