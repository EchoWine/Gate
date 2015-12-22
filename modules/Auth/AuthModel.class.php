<?php

class AuthModel extends Model{
	
	public $data;

	public function __construct(){
		$this -> name = 'Auth';
	}
	
	public function check(){
		$this -> checkLogin();

	}

	public function checkLogin(){
		if($this -> data['user'] !== null){
			$u = $this -> data['user'];
			$p = $this -> data['pass'];
		}
	}
}

?>