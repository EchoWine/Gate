<?php

namespace Test\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;

class Hodor extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'hodor';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		# ID
		$schema -> id();

		# Door
		$schema -> string('door')
				-> maxLength(128)
				-> minLength(3)
				-> required()
				-> default('Opened');

	}
}

?>