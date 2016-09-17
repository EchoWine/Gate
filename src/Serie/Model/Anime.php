<?php

namespace Serie\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;

class Anime extends Serie{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'anime';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		parent::setSchemaFields($schema);

	}
}

?>