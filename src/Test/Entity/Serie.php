<?php

namespace Test\Entity;

use CoreWine\Item\Entity;
use CoreWine\Item\Field\Schema as Field;

class Serie extends Entity{

	public static $__table = 'series';

	public static function __fields($schema){

		$schema -> field(Field\IdField::class,'id');
	
		$schema -> field(Field\StringField::class,'name');

	}
}

?>