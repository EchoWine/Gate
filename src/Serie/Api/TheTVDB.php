<?php

namespace Serie\Api;

class TheTVDB extends Basic{

	/**
	 * Name of api
	 *
	 * @param string
	 */
	protected $name = 'thetvdb';

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
	protected $url = "http://thetvdb.com/api/";

	public function requestDiscovery($params){
		$url = $this -> url."GetSeries.php?".http_build_query($params);
			
		# @temp

		if(!($resources = @simplexml_load_string(file_get_contents($url))))
			return [];


		$return = [];


		foreach($resources -> Series as $resource){
			$return[(int)$resource -> seriesid] = [
				'api' => $this -> getName(),
				'type' => 'series',
				'id' => (int)$resource -> seriesid,
				'language' => (string)$resource -> language,
				'name' => (string)$resource -> SeriesName,
				'banner' => (string)$resource -> banner,
				'overview' => (string)$resource -> Overview,
				'first_aired' => (string)$resource -> FirstAired,
				'network' => (string)$resource -> Network,
			];
		}

		return $return;
	}
	
	/**
	 * Discovery a resource
	 *
	 * @param string $keys
	 */
	public function discovery($key){

		return $this -> requestDiscovery(['seriesname' => str_replace("%20","_",$key)]);
	}




}