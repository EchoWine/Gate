<?php

namespace WT\Service;
use WT\Api as Api;
use WT\Model\Serie;
use WT\Model\Resource;
use Request;

class WT{

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
	 * @param string $user
	 * @param string $resource
	 * @param string $key
	 *
	 * @return array
	 */
	public static function discovery($user,$resource,$key){

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
	 * @param string $user
	 * @param string $resource
	 * @param string $source_name
	 * @param mixed $id
	 *
	 * @return array
	 */
	public static function add($user,$source_type,$source_name,$source_id){

		$response = [];

		$model = self::getModelByResource($source_type);

		if(!$model){
			throw new \Exception("Resource not valid");
		}

		$resource = Resource::where(['source_name' => $source_name,'source_id' => $source_id]) -> first();

		if($resource){

			if($resource -> users -> has($user)){

				# Some message ??

			}else{

				$resource -> users -> add($user);
				$resource -> users -> save();
			}

		}else{

			foreach(self::$sources as $source){

				$source = new $source();

				if($source -> getName() == $source_name){
					$response = $source -> add($source_id);
					break;
				}

			}

			$resource = Resource::create([
				'name' => $response -> name,
				'source_type' => $source_type,
				'source_name' => $source_name,
				'source_id' => $source_id,
				'updated_at' => (new \DateTime()) -> format('Y-m-d H:i:s')
			]);

			$detail = new $model();

			$detail -> name = $response -> name;
			$detail -> overview = $response -> overview;
			$detail -> status = $response -> status;
			$detail -> resource = $resource;

			$detail -> save();

			# TEMP-FIX
			$resource = Resource::where(['source_name' => $source_name,'source_id' => $source_id]) -> first();

			$resource -> users -> add($user);
			$resource -> users -> save();
		}

			
		return ['added'];
	}

	/**
	 * Get model given resource name
	 *
	 * @param string $resource
	 *
	 * @return string
	 */
	public static function getModelByResource($resource){
		switch($resource){
			case 'series':
				return Serie::class;
			break;
			default:
				return null;
			break;
		}
	}

	/**
	 * Get basic path api 
	 *
	 * @return string
	 */
	public static function url(){

		return Request::getDirUrl()."api/v1/";
	}
}

?>