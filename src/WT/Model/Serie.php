<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;

class Serie extends Resource{

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

		parent::fields($schema);

		$schema -> toMany(Season::class,'seasons','serie_id');

	}
}

?>