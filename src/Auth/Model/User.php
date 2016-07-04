<?php

namespace Auth\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;
use Auth\Service\Auth;

class User extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $__table = 'users';

	/**
	 * Called when schema is initialized
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		$schema -> field(Field\IDField::class,'id');
	
		$schema -> field(Field\StringField::class,'password')
				-> maxLength(128);

		$schema -> field(Field\StringField::class,'username');

		$schema -> field(Field\StringField::class,'email');

		$schema -> field(Field\CollectionModelField::class,'sessions')
				-> relation(Session::class)
				-> reference('user_id');

	}

	/**
	 * Seed
	 *
	 * @param Repository $repository
	 */
	public static function setSeed(){
		if(User::count() == 0){
			User::create([
				'username' => 'admin',
				'email' => 'admin@admin.com',
				'password' => Auth::getHashPass('admin')
			]);
		}

	}
	
}

?>