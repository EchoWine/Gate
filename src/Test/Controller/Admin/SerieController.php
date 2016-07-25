<?php

namespace Test\Controller\Admin;

use Admin\Controller\AdminController as BasicController;


class SerieController extends BasicController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Test\Model\Serie';

	/**
	 * Url
	 *
	 * @var
	 */
	public $url = 'series';


	/**
	 * Set views
	 *
	 * @param  $views
	 */
	public function views($views){

		$views -> all(function($view){
			$view -> id();
			$view -> name();
		});

		$views -> add(function($view){
			$view -> name();
		});

		$views -> edit(function($view){
			$view -> name();
		});

		$views -> get(function($view){
			$view -> id();
			$view -> name();
		});

		$views -> search(function($view){
			$view -> id();
			$view -> name();
		});
	}

}

?>