<?php

namespace WT\Api;

use CoreWine\Component\Str;
use CoreWine\Http\Client;
use CoreWine\Component\File;
use CoreWine\Component\DomDocument;
use CoreWine\Http\Request;

class BakaUpdates extends Basic{

	/**
	 * Name of api
	 *
	 * @param string
	 */
	protected $name = 'baka-updates';

	/**
	 * List of all resources that this api can retrieve
	 * 
	 * @var Array (series|anime|manga)
	 */
	protected $resources = ['manga'];

	/**
	 * Basic url
	 *
	 * @param string
	 */
	protected $url = "https://www.mangaupdates.com/";

	/**
	 * Discovery a resource
	 *
	 * @param string $keys
	 */
	public function discovery($key){

		return $this -> all($key);
	}

	/**
	 * Perform the request to the api in order to discovery new series
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function all($key){
		$key = str_replace("%20","+",$key);

		$client = new Client();
		$url = $this -> url."series.html?stype=title&search=%22".$key."%22&perpage=25&orderby=rating";
		$response = $client -> request($url);
		$dom = new DomDocument($response);
		$node = $dom -> getElementsByAttribute('class','text series_rows_table') -> item(0);
		$rows = $node -> getElementsByTagName("tr");

		$i = 0;

		$return = [];

		foreach($rows as $tr){
			
			# Skip first 2 rows
			if($i >= 2){
				
				$a = $tr -> getElementsByTagName('a') -> item(0);

				if($a == null)
					break;

				# URL
				$url = $a -> getAttribute('href');

				# ID Manga
				$url_parts = explode("id=",$url);

				# Ending...
				if(count($url_parts) == 1)
					break;

				$id = $url_parts[1];

				$name = $a -> nodeValue;

				# Name Manga
				// $name = $a -> getElementsByTagName('i') -> item(0) -> nodeValue;

				# Missing Poster
				# ...

				$response = $client -> request($url);
				$manga = new DomDocument($response);
				$overview = $manga -> getElementsByAttribute('class','sContainer') -> item(0) -> getElementsByTagName('div') -> item(2) -> nodeValue;


				$banner = $manga -> getElementsByAttribute('class','sContainer') -> item(1) -> getElementsByTagName('img') -> item(0) -> getAttribute('src');
				$basename = basename($banner);
				$destination = 'uploads/baka-updates/'.$basename;


				if(!file_exists(dirname($destination))){
					mkdir(dirname($destination),0777,true);
				}
				
				if(!file_exists($destination)){	
					$client -> download($banner,$destination);
				}

				$banner = Request::getDirUrl()."/".$destination;
				

				if(isset($return[$id])){
					$return[$id]['alias'][] = $name;
				}else{
					$return[$id] = ['id' => $id,'name' => $name,'overview' => $overview,'banner' => $banner,'alias' => [$name]];
				}			

			}

			$i++;
		}
		
		return $return;
	}

	public function get($id){

		
	}
	
	/**
	 * Add a resource
	 *
	 * @param string $id
	 */
	public function add($id){


		return $this -> get($id);
	}


	public function update(){
		
	}

}