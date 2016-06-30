<?php

namespace Test\Entity;

use CoreWine\Item\Entity;
use CoreWine\Item\Field\Schema as Field;

class Hodor extends Entity{

	public static $__table = 'hodor';

	public static function __fields($schema){

		# ID
		$schema -> field(Field\IdField::class,'id')
				-> label('#');

		# Foo
		$schema -> field(Field\StringField::class,'door')
				-> label('door')
				-> maxLength(128)
				-> minLength(3)
				-> required()
				-> default('Opened');

	}
}

?>