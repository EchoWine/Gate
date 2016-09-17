<?php

namespace Test\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;

class Example extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'examples';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		$schema -> id();

		$schema -> string('string')
				-> maxLength(128)
				-> required();

		$schema -> timestamp('timestamp');

		$schema -> text('text');


	}
}

?>