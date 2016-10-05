<?php

namespace WT\Api;

use WT\Api\TheTVDB as Object;
use CoreWine\Component\Str;
use CoreWine\Http\Client;

class TheTVDB extends Basic{

	/**
	 * Name of api
	 *
	 * @param string
	 */
	protected $name = 'thetvdb';

	/**
	 * Token api
	 *
	 * @param string
	 */
	protected $token = '2216193F17A3C7A4';

	/**
	 * List of all resources that this api can retrieve
	 * 
	 * @var Array (series|anime|manga)
	 */
	protected $resources = ['anime','series'];

	/**
	 * Basic api url
	 *
	 * @param string
	 */
	protected $url_api = "http://www.thetvdb.com/api/";

	/**
	 * Basic api url
	 *
	 * @param string
	 */
	protected $url_public = "http://www.thetvdb.com/";

	/**
	 * Perform the request to the api in order to discovery new series
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function all($params){


		$return = [];

		$client = new Client();

		try{

			# Search for series
			$response = $client -> request($this -> url_api."GetSeries.php",'GET',$params);
			$resources = Str::xml($response);

		}catch(Exception $e){

			return ['error' => $e -> getMessage()];

		}

		if(!isset($resources -> Series))
			return $return;


		foreach($resources -> Series as $resource){

			try{

				# Send request for banners
				$response = $client -> request($this -> url_api.$this -> token."/series/".$resource -> seriesid."/banners.xml");

				if(!($banners = Str::xml($response)))
					return $return;


			
				foreach($banners -> Banner as $banner){
					if($banner -> BannerType == 'poster'){

						# Get image
						$response = $client -> request($this -> url_public."banners/".$banner -> BannerPath);

						if($response){

							# Save image
							$banner = $this -> url_public."banners/".$banner -> BannerPath;
							break;
						}else{
							$banner = '';
						}
					}
				}
			}catch(\Exception $e){

			}

			$resource = Object\SerieObject::short($resource);
			$return[$resource -> id] = [
				'source' => $this -> getName(),
				'type' => 'series',
				'id' => $resource -> id,
				'language' => $resource -> language,
				'name' => $resource -> name,
				'banner' => $banner,
				'overview' => $resource -> overview,
				'first_aired' => $resource -> first_aired_at,
				'network' => $resource -> network,
			];
		}


		return $return;
	}

	public function get($id){

		
		$client = new Client();

		try{

			$response = $client -> request($this -> url_api.$this -> token."/series/".$id."/all/en.xml");
			$resource = Str::xml($response);

		}catch(Exception $e){

			return ['error' => $e -> getMessage()];

		}

		return Object\SerieObject::long($resource);

	}

	
	
	/**
	 * Discovery a resource
	 *
	 * @param string $keys
	 */
	public function discovery($key){

		return $this -> all(['seriesname' => str_replace("%20","_",$key)]);
	}

	/**
	 * Add a resource
	 *
	 * @param string $id
	 */
	public function add($id){


		return $this -> get($id);
	}



}