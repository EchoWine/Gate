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

			if($source -> isResource($resource)){
				$response[$source -> getName()] = $source -> discovery($key);

				foreach($response[$source -> getName()] as $n => $k){
					$resource = Resource::where(['source_name' => $source -> getName(),'source_id' => $k['id']]) -> first();
					
					if($resource){
						$u = $resource -> users -> has($user);
						$r = 1;
					}else{
						$r = 0;
						$u = 0;
					}
					
					$response[$source -> getName()][$n]['library'] = $r;
					$response[$source -> getName()][$n]['user'] = $u;

				}
			}

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

		try{
			$response = [];

			$model = self::getModelByResource($source_type);

			if(!$model)
				throw new \Exception("Resource type name invalid");

			$resource = Resource::where(['source_name' => $source_name,'source_id' => $source_id]) -> first();

			if($resource){

				if($resource -> users -> has($user)){

					# Some message ??
					return ['message' => 'Already added','status' => 'info'];

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
				$detail -> poster() -> setByUrl($response -> poster);

				$detail -> save();

				# TEMP-FIX
				$resource = Resource::where(['source_name' => $source_name,'source_id' => $source_id]) -> first();

				$resource -> users -> add($user);
				$resource -> users -> save();
			}

		}catch(\Exception $e){

			return ['message' => $e -> getMessage(),'status' => 'error'];
		}
			
		return ['message' => 'Resource added','status' => 'success'];
		
	}

	/**
	 * Delete a resource
	 *
	 * @param string $user
	 * @param string $resource
	 * @param string $source_name
	 * @param mixed $id
	 *
	 * @return array
	 */
	public static function delete($user,$source_type,$source_name,$source_id){

		try{
			$response = [];

			$model = self::getModelByResource($source_type);

			if(!$model)
				throw new \Exception("Resource type name invalid");
			
			$resource = Resource::where(['source_name' => $source_name,'source_id' => $source_id]) -> first();

			if(!$resource)
				throw new \Exception("The resource doesn't exists");

			if(!$resource -> users -> has($user))
				throw new \Exception("The resource insn't in library");


			$resource -> users -> remove($user);
			$resource -> users -> save();


		}catch(\Exception $e){

			return ['status' => 'error','message' => $e -> getMessage()];
		}
			
		
		return ['status' => 'success','message' => 'Deleted'];
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