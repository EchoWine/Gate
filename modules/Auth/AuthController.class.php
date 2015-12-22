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
			'user' => new stdDataPost('Username',$this -> cfg['data']['post_user']),

			# Username
			'pass' => new stdData('Password',$this -> cfg['data']['post_pass'])

				
		];
	}

}

?>