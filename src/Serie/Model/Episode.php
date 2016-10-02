<?php

namespace Serie\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;


class Episode extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'episodes';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		
		$schema -> id();
	
		$schema -> string('name');

		$schema -> toOne(Season::class,'season');

	}

}

?>