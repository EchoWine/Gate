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
	 * View list
	 *
	 * @var array
	 */
	public $view_list = ['id','name','serie'];

	/**
	 * View add
	 *
	 * @var array
	 */
	public $view_add = ['name','serie'];

	/**
	 * View edit
	 *
	 * @var array
	 */
	public $view_edit = ['name','serie'];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_get = ['id','name','serie'];

	/**
	 * View search
	 *
	 * @var array
	 */
	public $view_search = ['id','name','serie'];

}

?>