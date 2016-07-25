<?php

namespace Test\Controller\Admin;

use Admin\Controller\AdminController as BasicController;


class ExampleController extends BasicController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Test\Model\Example';

	/**
	 * Url
	 *
	 * @var
	 */
	public $url = 'examples';

	/**
	 * Set views
	 *
	 * @param  $views
	 */
	public function views($views){

		$views -> all(function($view){
			$view -> id();
			$view -> string();
			$view -> timestamp() -> label('time');
			$view -> text();
		});

		$views -> add(function($view){
			$view -> string();
			$view -> timestamp();
			$view -> text();
		});

		$views -> edit(function($view){
			$view -> string();
			$view -> timestamp();
			$view -> text();
		});

		$views -> get(function($view){
			$view -> id();
			$view -> string();
			$view -> timestamp();
			$view -> text();
		});

		$views -> search(function($view){
			$view -> id();
			$view -> string();
			$view -> timestamp();
			$view -> text();
		});
	}

}

?>