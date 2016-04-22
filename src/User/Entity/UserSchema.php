<?php

namespace User\Controller;

use Item\Entity as Item;

class UserSchema extends Item\ItemSchema{
	
	
	public static $table = 'user';

	public static $fields = [
		'username' => [
			'type' => Item\FieldString::class,
			'required' => true
		],
		'password' => [
			'type' => Item\FieldString::class,
			'required' => true
		],
		'email' => [
			'type' => Item\FieldString::class,
			'required' => true,
		]
	];

}

?>