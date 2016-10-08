<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;

class Resource extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'resources';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		$schema -> id();
	
		$schema -> string('name');

		$schema -> string('source_type');
	
		$schema -> string('source_name');
	
		$schema -> string('source_id');

		$schema -> datetime('updated_at');

        $schema -> toMany(ResourceUser::class,'resource_users','resource_id')
                -> to('users','user');



	}
}

?>