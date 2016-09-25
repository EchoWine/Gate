<?php

namespace Serie\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;

abstract class Resource extends Model{

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		$schema -> id();
	
		$schema -> string('name');
	
		$schema -> string('source_name');
	
		$schema -> string('source_id');

	}
}

?>