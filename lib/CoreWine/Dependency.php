<?php

namespace CoreWine;

use CoreWine\Exceptions\PHPVersionException;

class Dependency{

	/**
	 * Version PHP
	 *
	 * @var
	 */
	public static $PHP_version;

	/**
	 * Set version PHP
	 *
	 * @param string $version
	 */
	public static function setPHPVersion($version){
		static::$PHP_version = $version;
	}

	/**
	 * Get version PHP
	 *
	 * @return string
	 */
	public static function getPHPVersion(){
		return static::$PHP_version;
	}

	/**
	 * Check
	 */
	public static function check(){
		static::checkPHPVersion();
	}

	/**
	 * Check version PHP
	 */
	public static function checkPHPVersion(){
		if(version_compare(PHP_VERSION, static::getPHPVersion(), '<')){
			throw new PHPVersionException("PHP: ".PHP_VERSION.". Required version PHP: ".static::getPHPVersion());
		}
	}
	
}

?>