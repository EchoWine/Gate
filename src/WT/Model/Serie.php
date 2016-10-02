<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;

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
	public static function fields($schema){

		$schema -> id();
		
		$schema -> string('name');

		$schema -> string('genres');

		$schema -> text('overview');

		$schema -> string('status');

		$schema -> toOne(Resource::class,'resource');

		$schema -> toMany(Season::class,'seasons','serie_id');

	}
}

?>