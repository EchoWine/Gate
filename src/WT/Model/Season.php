<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;

class Season extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'seasons';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		$schema -> id();
	
		$schema -> integer('number');

		$schema -> toOne(Serie::class,'serie');

		$schema -> toMany(Episode::class,'episodes','serie_id');

	}
}

?>