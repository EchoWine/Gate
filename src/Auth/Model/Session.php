<?php

namespace Auth\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;


class Session extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'sessions';

	/**
	 * Called when schema is initialized
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		$schema -> id();
	
		$schema -> string('sid')
				-> maxLength(128);

		$schema -> timestamp('expire');

		$schema -> toOne(User::class,'user');

	}

}

?>