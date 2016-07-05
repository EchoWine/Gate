<?php

namespace Test\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;

class Hodor extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $__table = 'hodor';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		# ID
		$schema -> field(Field\IDField::class,'id')
				-> label('#');

		# Foo
		$schema -> field(Field\StringField::class,'door')
				-> label('door')
				-> maxLength(128)
				-> minLength(3)
				-> required()
				-> default('Opened');

	}
}

?>