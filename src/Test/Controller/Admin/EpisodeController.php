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

		$views -> all(function($view){
			$view -> id();
			$view -> name();
			$view -> serie('series') -> name() -> label('name of serie');
			$view -> prev('episodes') -> name() -> label('prev episode');
			$view -> next('episodes') -> name() -> label('next episode');
			$view -> next('episodes') -> prev('episodes') -> name() -> label('next prev episode = current');

		});

		$views -> add(function($view){
			$view -> name();
			$view -> serie('series') -> label('serie') -> select('series','id',"#{id} - {name}","'#';id;' - ';name");
		});

		$views -> edit(function($view){
			$view -> name();
			// $view -> serie('series') -> label('serie') -> select('series','id',"#{id} - {name}","'#';id;' - ';name");
			$view -> serie('series') -> label('serie') -> select('series','id',"#{id} - {name}");
		});

		$views -> get(function($view){
			$view -> id();
			$view -> name();
			$view -> serie('series') -> name() -> label('name of serie');
		});

		$views -> search(function($view){
			$view -> id();
			$view -> name();
			$view -> serie('series') -> name() -> label('name of serie');
			$view -> prev('episodes') -> name() -> label('prev episode');
			$view -> next('episodes') -> name() -> label('next episode');
			$view -> next('episodes') -> prev('episodes') -> name() -> label('next prev episode = current');
		});
	}

}

?>