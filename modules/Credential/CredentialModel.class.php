<?php

class CredentialModel extends Model{
	
	public $data;

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