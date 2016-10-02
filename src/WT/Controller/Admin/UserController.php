<?php

namespace WT\Controller\Admin;

class UserController extends AdminController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Auth\Model\User';

	/**
	 * Url
	 *
	 * @var
	 */
	public $url = 'users';

	/**
	 * Set views
	 *
	 * @param  $views
	 */
	public function views($views){

		$views -> all(function($view){
			$view -> id();
			$view -> username();
			$view -> email();
		});

		$views -> add(function($view){
			$view -> username();
			$view -> email();
			$view -> token();

		});

		$views -> edit(function($view){
			$view -> username();
			$view -> email();
			$view -> token();

		});

		$views -> get(function($view){
			$view -> id();
			$view -> username();
			$view -> email();
			$view -> token();

		});

		$views -> search(function($view){
			$view -> id();
			$view -> username();
			$view -> email();
			$view -> token();

		});
	}

}

?>