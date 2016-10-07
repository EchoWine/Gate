<?php

namespace Auth\Model;

use CoreWine\DataBase\ORM\Model;
use CoreWine\DataBase\ORM\Field\Schema as Field;
use Auth\Field\Schema as AuthField;

use WT\Model\ResourceUser;

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
	public static function fields($schema){

		$schema -> id();
	
		$schema -> password()
				-> maxLength(128);

		$schema -> string('username')
				-> required()
				-> unique();

		$schema -> email();

		$schema -> string('token');

		$schema -> toMany(Session::class,'sessions','user_id');

        $schema -> toMany(ResourceUser::class,'user_resources','user_id')
                -> to('resources','resource');


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