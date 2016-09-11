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
	 * Discovery new serie
	 *
	 * @param string $resource
	 * @param string $key
	 *
	 * @return array
	 */
	public static function discovery($resource,$key){

		foreach(self::$sources as $source){

			$source = new $source();

			if($source -> isResource($resource))
				$response[$source -> getName()] = $source -> discovery($key);

		}

		return $response;
	}
}

?>