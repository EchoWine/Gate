<?php

class AuthController extends Controller{
	public $model;
	public $cfg;

	public function __constructor($model,$cfg){
		$this -> model = $model;
		$this -> cfg = $cfg;
	}

	public function setData(){
		return [

			# Username
			'user' => new stdDataPost('Mail',$this -> cfg['data']['post_mail']),

			# Username
			'pass' => new stdDataPost('Password',$this -> cfg['data']['post_pass'])
				
		];
	}

}

?>