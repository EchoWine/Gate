<?php

namespace WT\Controller\Admin;

class SerieController extends AdminController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'WT\Model\Serie';

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