<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;

class Genre extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'genres';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		$schema -> id();

		$schema -> string('name') -> required();

	}
}

?>