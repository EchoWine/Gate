<?php

namespace Test\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;

class Serie extends Model{

	public static $__table = 'series';

	public static function setSchemaFields($schema){

		$schema -> field(Field\IDField::class,'id');
	
		$schema -> field(Field\StringField::class,'name');

		$schema -> field(Field\CollectionModelField::class,'episodes')
				-> relation(Episode::class)
				-> reference('serie_id');

	}
}

?>