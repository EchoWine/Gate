<?php

namespace Test\Controller\Admin;

use Admin\Controller\AdminController as BasicController;


class EpisodeController extends BasicController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Test\Model\Episode';

	/**
	 * Url
	 *
	 * @var
	 */
	public $url = 'episodes';

	/**
	 * Set views
	 *
	 * @param $views
	 */
	public function views($views){

		$form = function($view){
			$view -> name() -> label('Name');
			// $view -> serie('series') -> label('serie') -> select('series','id',"#{id} - {name}","'#';id;' - ';name");
			$view -> serie('series') -> label('Serie') -> select('series','id',"#{id} - {name}");
		};

		$list = function($view){
			$view -> id() -> label('#');
			$view -> name() -> label('Name');
			$view -> serie('series') -> name() -> label('Serie');
			$view -> prev('episodes') -> name() -> label('Prev episode');
			$view -> next('episodes') -> name() -> label('Next episode');
			$view -> next('episodes') -> prev('episodes') -> name() -> label('Next prev episode = current');
		};

		$get = function($view){
			$view -> id() -> label('#');
			$view -> name();
			$view -> serie('series') -> name() -> label('Serie');
		};

		$views -> all($list);
		$views -> add($form);
		$views -> edit($form);
		$views -> get($get);
		$views -> search($list);
	}

}

?>