<?php

class AuthModel extends Model{
	
	public $data;

	public function __construct(){
		$this -> name = 'Auth';
	}
	
	public function alterTable(){
		
		$this -> alterTableCredential();
		$this -> alterTableSession();
	}

	public function alterTableSession(){
		$c = $this -> cfg['session'];
		$col = $c['col'];
		$table = $c['table'];

		DB::table($table) -> column($col['uid']) -> type('bigint') -> alter();
		DB::table($table) -> column($col['sid']) -> type('string') -> primary() -> index() -> alter();
		DB::table($table) -> column($col['expire']) -> type('timestamp') -> alter();
	}

	public function alterTableCredential(){
		$c = $this -> cfg['credential'];
		$col = $c['col'];
		$table = $c['table'];
		$def = $c['default'];

		DB::table($table) -> column($col['id']) -> type('id') -> alter();
		DB::table($table) -> column($col['user']) -> type('string') -> alter();
		DB::table($table) -> column($col['pass']) -> type('string') -> alter();
		DB::table($table) -> column($col['mail']) -> type('string') -> alter();

		if(DB::table($table) -> count() == 0){
			DB::table($table) -> insert([
				$col['user'] => $def['user'],
				$col['mail'] => $def['mail'],
				$col['pass'] => self::getHashPass($def['pass'])
	 
			]);
		}
	}

	public function cleanSession(){
		$s_col = $this -> cfg['session']['col'];
		$s_table = $this -> cfg['session']['table'];

		DB::table($s_table) -> where($s_col['expire'],'<',time()) -> delete();
	}

	public function checkSession(){
		$sid = Cookie::getCookie($this -> cfg['cookie']);


		if(!empty($sid)){
			$s_col = $this -> cfg['session']['col'];
			$s_table = $this -> cfg['session']['table'];

			$q = DB::table($s_table) -> where($s_col['sid'],$sid);
			if($q -> count() == 1){
				return true;
			}else
				Cookie::removeCookie($this -> cfg['cookie']);
			
		}

		return false;
	}

	public function deleteSessionByUID($uid){
		
		return DB::table($this -> cfg['session']['table'])
		-> where($this -> cfg['session']['col']['uid'],$uid) 
		-> get();
	}

	public function checkAttemptLogout(){

		# Delete from table
		$this -> deleteSessionByUID(Cookie::getCookie($this -> cfg['cookie']));

		DB::printLog();

		# Delete from cookies
		Cookie::removeCookie($this -> cfg['cookie']);

		# Refresh
		http::refresh();
	}

	public function checkAttemptLogin($user,$pass){

		$c_col = $this -> cfg['credential']['col'];
		$c_table = $this -> cfg['credential']['table'];
		$s_col = $this -> cfg['session']['col'];
		$s_table = $this -> cfg['session']['table'];

		$q = DB::table($c_table)
			-> where($c_col['user'],$user)
			-> where($c_col['pass'],$pass)
			-> get();		

		$r = [];

		$pass = self::getHashPass($pass);
		

		if(!empty($q)){

			# User id
			$uid = $q[$c_col['id']];

			# Session id
			$sid = $this -> getNewSID();

			$expire = time()+$this -> cfg['expire'];

			DB::table($s_table) -> insert([
				$s_col['uid'] => $uid,
				$s_col['sid'] => $sid,
				$s_col['expire'] => $expire
			]);

			Cookie::setCookie($this -> cfg['cookie'],$sid,$expire);

			http::refresh();

			return new stdResponse(1,'Success to login','Success');
		}else{

			if($this -> cfg['ambiguous']){
				
				$r[] = 'The data entered is incorrect';

			}else{

				if($q[$col['user']] !== $user){
					$r[] = 'Wrong username/email';
				}

				if($q[$col['pass']] !== $pass){
					$r[] = 'Wrong password';
				}

			}


		}

		return new stdResponse(0,'Failed to login',$r);
	}

	public function getNewSID(){

		do{
			$sid = md5(microtime());
			$q = DB::table($this -> cfg['session']['table'])
			-> where($this -> cfg['session']['col']['sid'],$sid)
			-> count();
		}while($q == 1);

		return $sid;

	}

	public static function getHashPass($p){
		# return sha1($p);
		return $p;
	}
}

?>