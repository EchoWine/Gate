<?php

namespace WT\Service;
use WT\Api as Api;
use WT\Model\Serie;
use WT\Model\Season;
use WT\Model\Episode;
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
				$detail -> banner() -> setByUrl($response -> banner);
				$detail -> updated_at = (new \DateTime()) -> format('Y-m-d H:i:s'); 
				$detail -> save();

				if($source_type == 'series' && $source -> isResource('series')){

					foreach($response -> episodes as $r_episode){

						$season = Season::firstOrCreate([
							'number' => $r_episode -> season,
							'serie_id' => $detail -> id
						]);

						$episode = new Episode();
						$episode -> name = $r_episode -> name;
						$episode -> number = $r_episode -> number;
						$episode -> overview = $r_episode -> overview;
						$episode -> aired_at = $r_episode -> aired_at;
						$episode -> update_at = $r_episode -> updated_at;
						$episode -> season = $season;
						$episode -> season_n = $r_episode -> season;
						$episode -> serie_id = $detail -> id;
						$episode -> save();

					}
				}


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
	 * Get a resource
	 *
	 * @param string $user
	 * @param string $resource
	 * @param string $source_name
	 * @param mixed $id
	 *
	 * @return array
	 */
	public static function get($user,$source_type,$source_name,$source_id){

		$model = self::getModelByResource($source_type);

		$model = $model::where('id',$source_id) -> first();
		

		return $model -> toArray();
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
	public static function sync($user,$resource_type,$id){

		try{
			$response = [];

			$model = self::getModelByResource($resource_type);

			if(!$model)
				throw new \Exception("Resource type name invalid");

			$resource = $model::where(['id' => $id]) -> first();

			if(!$resource){

				throw new \Exception("Resource not found");

				

			}else{

				$source_name = $resource -> resource -> source_name;
				$source_id = $resource -> resource -> source_id;

				foreach(self::$sources as $source){

					$source = new $source();

					if($source -> getName() == $source_name){
						$response = $source -> get($source_id);
						break;
					}

				}

				$resource_node = $resource -> resource;

				$resource -> name = $response -> name;
				$resource -> overview = $response -> overview;
				$resource -> status = $response -> status;
				$resource -> resource = $resource;
				$resource -> poster() -> setByUrl($response -> poster);
				$resource -> banner() -> setByUrl($response -> banner);
				$resource -> updated_at = (new \DateTime()) -> format('Y-m-d H:i:s'); 
				$resource -> save();

				if($resource_type == 'series' && $source -> isResource('series')){

					foreach($response -> episodes as $r_episode){

						$season = Season::firstOrCreate([
							'number' => $r_episode -> season,
							'serie_id' => $resource -> id
						]);

						$episode = Episode::firstOrCreate([
							'number' => $r_episode -> number,
							'season_n' => $r_episode -> season,
							'season_id' => $season -> id,
							'serie_id' => $resource -> id
						]);

						$episode -> name = $r_episode -> name;
						$episode -> overview = $r_episode -> overview;
						$episode -> aired_at = $r_episode -> aired_at;
						$episode -> update_at = $r_episode -> updated_at;
						$episode -> save();

					}
				}

			}

		}catch(\Exception $e){

			return ['message' => $e -> getMessage(),'status' => 'error'];
		}
			
		return ['message' => 'Resource updated','status' => 'success'];
		
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