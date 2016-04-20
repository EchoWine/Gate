<?php

namespace Auth\Repository;

use CoreWine\DB as DB;
use CoreWine\Cfg;

class AuthRepository{

	const TABLE_SESSION = 'session';
	const TABLE_USER = 'user';

	public static function alterSchema(){
		AuthRepository::alterSchemaSession();
		AuthRepository::alterSchemaUser();
	}

	public static function alterSchemaSession(){

		DB::schema(AuthRepository::TABLE_SESSION,function($table){
			$table -> bigint('user_id');
			$table -> string('sid',128) -> primary();
			$table -> timestamp('expire');
		});
	}

	public static function user(){
		return DB::table(AuthRepository::TABLE_USER);
	}

	public static function session(){
		return DB::table(AuthRepository::TABLE_SESSION);
	}

	public static function alterSchemaUser(){

		DB::schema(AuthRepository::TABLE_USER,function($table){
			$table -> id();
			$table -> string('password',128);
			$table -> string('username');
			$table -> string('email');
		});

		
	}

	public static function removeSessionExpired(){
		return DB::table(AuthRepository::TABLE_SESSION) -> where('expire','<',time()) -> delete();
	}

	public static function getUserBySID($sid){
		return DB::table(AuthRepository::TABLE_SESSION) -> where('sid',$sid) 
		-> rightJoin(AuthRepository::TABLE_USER,'user_id','id')
		-> get();

	}


	/**
	 * Delete session of user using sid
	 *
	 * @param string $sid
	 */
	public static function deleteSessionBySID($sid){
		
		return DB::table(AuthRepository::TABLE_SESSION)
		-> where('sid',$sid) 
		-> delete();
	}

	/**
	 * Get new SID that isn't already used
	 *
	 * @return string sid
	 */
	public static function generateSID(){

		do{
			$sid = md5(microtime());
			$q = AuthRepository::session()
			-> where('sid',$sid)
			-> count();
		}while($q == 1);

		return $sid;
	}

	public static function getUsersByRaw($usernameOrEmail,$password){
		
		# Building query
		$q = AuthRepository::user() -> where('password',$password);
		
		if(Cfg::get('Auth.login_user'))
			$q = $q -> orWhere('username',$usernameOrEmail);

		if(Cfg::get('Auth.login_mail'))
			$q = $q -> orWhere('email',$usernameOrEmail);

		# Execute query
		$q = $q -> lists();

		return $q;


	}

}	

?>