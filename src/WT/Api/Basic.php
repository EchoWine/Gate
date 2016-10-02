<?php

namespace WT\Api;

class Basic{

	/**
	 * Name of api
	 *
	 * @param string
	 */
	protected $name;

	/**
	 * List of all resources that this api can retrieve
	 * 
	 * @var Array (series|anime|manga)
	 */
	protected $resources = [];

	/**
	 * Basic api url
	 *
	 * @param string
	 */
	protected $url;


	/**
	 * Construct
	 */
	public function __construct(){}

	/**
	 * Get name of api
	 *
	 * @return string
	 */
	public function getName(){
		return $this -> name;
	}

	/**
	 * Can i research this type of resource?
	 *
	 * @param string $resource
	 *
	 * @return bool
	 */
	public function isResource($resource){
		return $resource == 'all' || in_array($resource,$this -> resources);
	}

	/**
	 * Request a discovery
	 *
	 * @param array $params
	 */
	public function discoveryRequest($params){}

	/**
	 * Discovery a resource
	 *
	 * @param string $key
	 */
	public function discovery($key){}




}