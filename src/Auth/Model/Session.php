<?php

namespace Auth\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;


class Session extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $__table = 'sessions';

	/**
	 * Called when schema is initialized
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		$schema -> field(Field\IDField::class,'id');
	
		$schema -> field(Field\StringField::class,'sid')
				-> maxLength(128);

		$schema -> field(Field\TimestampField::class,'expire');

		$schema -> field(Field\ModelField::class,'user')
				-> relation(User::class);

	}

}

?>