<?php

namespace Test\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;

class Episode extends Model{


	public static $__table = 'episodes';

	public static function setSchemaFields($schema){

		
		$schema -> field(Field\IDField::class,'id');
	
		$schema -> field(Field\StringField::class,'name');

		$schema -> field(Field\ModelField::class,'serie')
				-> relation(Serie::class);

	}

}

?>