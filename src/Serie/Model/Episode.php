<?php

namespace Serie\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;


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
	public static function setSchemaFields($schema){

		
		$schema -> id();
	
		$schema -> string('name');

		$schema -> toOne(Serie::class,'serie');

		$schema -> toOne(Episode::class,'next');

		$schema -> toOne(Episode::class,'prev');



	}

}

?>