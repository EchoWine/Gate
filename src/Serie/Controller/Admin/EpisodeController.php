<?php

namespace Serie\Controller\Admin;

class EpisodeController extends AdminController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Serie\Model\Episode';

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
			$view -> prev('episodes') -> label('Previous') -> select('episodes','id',"#{id} - {name}");
			$view -> next('episodes') -> label('Next') -> select('episodes','id',"#{id} - {name}");
		};

		$list = function($view){
			$view -> id() -> label('#');
			$view -> name() -> label('Name');
			$view -> serie('series') -> name() -> label('Serie');
			$view -> prev('episodes') -> name() -> label('Prev episode');
			$view -> next('episodes') -> name() -> label('Next episode');
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