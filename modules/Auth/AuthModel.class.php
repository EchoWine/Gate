<?php

class AuthModel extends Model{
	
	/**
	 * Construct
	 */
	public function __construct(){
		$this -> name = 'Auth';
	}
	
	/**
	 * Alter the database
	 */
	public function alterTable(){
		
		$this -> alterTableCredential();
		$this -> alterTableSession();
	}

	/**
	 * Add table Session if doesn't exists
	 */
	public function alterTableSession(){
		$c = $this -> cfg['session'];
		$col = $c['col'];
		$table = $c['table'];

		DB::table($table) -> column($col['uid']) -> type('bigint') -> alter();
		DB::table($table) -> column($col['sid']) -> type('string') -> primary() -> index() -> alter();
		DB::table($table) -> column($col['expire']) -> type('timestamp') -> alter();
	}

	/**
	 * Add table Credential if doesn't exists
	 */
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

	/**
	 * Delete all session expired
	 */
	public function cleanSession(){
		$s_col = $this -> cfg['session']['col'];
		$s_table = $this -> cfg['session']['table'];

		DB::table($s_table) -> where($s_col['expire'],'<',time()) -> delete();
	}

	/**
	 * Get current SID saved in cookie or session
	 * @return (string) sid
	 */
	public function getSID(){

		$sid = Cookie::getCookie($this -> cfg['cookie']);

		if(empty($sid))
			$sid = Cookie::getSession($this -> cfg['cookie']);

		return $sid;
	}

	/**
	 * Check if current user is logged
	 * @return (bool) is user logged
	 */
	public function checkSession(){

		$sid = $this -> getSID();

		if(!empty($sid)){
			$s_col = $this -> cfg['session']['col'];
			$s_table = $this -> cfg['session']['table'];
			$c_col = $this -> cfg['credential']['col'];
			$c_table = $this -> cfg['credential']['table'];

			$q = DB::table($s_table) -> where($s_col['sid'],$sid) 
			-> leftJoin($c_table,$s_col['uid'],$c_col['id'])
			-> get();
			if(count($q) > 0){
				
				return (object)[
					'user' => $q[$c_col['user']],
					'mail' => $q[$c_col['mail']],
				];

			}else
				Cookie::removeCookie($this -> cfg['cookie']);
			
		}

		return [];
	}

	/**
	 * Delete session of user using uid
	 * @param (int) user id
	 */
	public function deleteSessionByUID($uid){
		
		DB::table($this -> cfg['session']['table'])
		-> where($this -> cfg['session']['col']['uid'],$uid) 
		-> delete();
	}

	/**
	 * Check attempt logout
	 */
	public function checkAttemptLogout(){

		# Delete from table
		$this -> deleteSessionByUID($sid = $this -> getSID());

		# Delete from cookies
		Cookie::removeCookie($this -> cfg['cookie']);
		Cookie::removeSession($this -> cfg['cookie']);

		# Refresh
		http::refresh();
	}

	/**
	 * Check attempt login
	 * @param (string $user username or email
	 * @param (string) $pass password
	 * @return (object) response
	 */
	public function checkAttemptLogin($user,$pass,$type){


		$cfg = $this -> cfg;
		$c_col = $cfg['credential']['col'];
		$c_table = $cfg['credential']['table'];
		$s_col = $cfg['session']['col'];
		$s_table = $cfg['session']['table'];

		$type = $type == 1 ? $cfg['remember'] : $cfg['normal'];

		$q = DB::table($c_table) -> where($c_col['pass'],$pass);
		
		if($cfg['login_user'] == 1)
			$q = $q -> orWhere($c_col['user'],$user);

		if($cfg['login_mail'] == 1)
			$q = $q -> orWhere($c_col['mail'],$user);

		$q = $q -> lists();		

		$r = [];

		$pass = self::getHashPass($pass);
		
		$cq = count($q);

		if($cq > 1){
			$r[] = 'Unable to determine a single user with this data';

		}else if($cq == 1){
			$q = $q[0];

			# User id
			$uid = $q[$c_col['id']];

			# Session id
			$sid = $this -> getNewSID();

			$expire = time()+$type['expire'];

			DB::table($s_table) -> insert([
				$s_col['uid'] => $uid,
				$s_col['sid'] => $sid,
				$s_col['expire'] => $expire
			]);

			if($type['data'] == 0)
				Cookie::setCookie($this -> cfg['cookie'],$sid,$expire);
			else
				Cookie::setSession($this -> cfg['cookie'],$sid);

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

	/**
	 * Get new SID that isn't already used
	 * @return (string) sid
	 */
	public function getNewSID(){

		do{
			$sid = md5(microtime());
			$q = DB::table($this -> cfg['session']['table'])
			-> where($this -> cfg['session']['col']['sid'],$sid)
			-> count();
		}while($q == 1);

		return $sid;

	}

	/**
	 * Get hash password
	 * @param (string) password
	 * @return (string) hash password
	 */
	public static function getHashPass($p){
		# return sha1($p);
		return $p;
	}

	/**
	 * Get current display name (user or email)
	 * @param (object) current info of user
	 * @return (string) display name
	 */
	public function getUserDisplay($i){
		return $this -> cfg['display'] == 0 ? $i -> user : $i -> mail;
	}

	
}

?>