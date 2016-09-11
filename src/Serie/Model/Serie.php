<?php

namespace Serie\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;

class Serie extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'series';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		$schema -> id();
	
		$schema -> string('name');

		$schema -> toMany(Season::class,'seasons','serie_id');

	}
}

?>