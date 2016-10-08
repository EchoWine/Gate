<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;


class Episode extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'episodes';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		
		$schema -> id();
	
		$schema -> string('name');
	
		$schema -> integer('number');

		$schema -> integer('season_n');

		$schema -> datetime('aired_at');

		$schema -> datetime('updated_at');

		$schema -> text('overview');

		$schema -> toOne(Season::class,'season');

		$schema -> toOne(Resource::class,'resource');

		$schema -> toOne(Episode::class,'episodes','serie_id');
	}

}

?>