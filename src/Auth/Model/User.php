<?php

namespace Auth\Model;

use CoreWine\ORM\Model;
use CoreWine\ORM\Field\Schema as Field;
use Auth\Field\Schema as AuthField;

class User extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'users';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){

		$schema -> id();
	
		$schema -> password()
				-> maxLength(128);

		$schema -> string('username');

		$schema -> string('email');

		$schema -> toMany(Session::class,'sessions','user_id');

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
				'password' => 'admin'
			]);
		}

	}
	
}

?>