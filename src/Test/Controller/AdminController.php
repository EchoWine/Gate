<?php

namespace Test\Controller;

use Admin\Controller\AdminController as BasicController;


class AdminController extends BasicController{

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
	 * View list
	 *
	 * @var array
	 */
	public $view_list = ['id','string','timestamp'];

	/**
	 * View add
	 *
	 * @var array
	 */
	public $view_add = ['string','timestamp'];

	/**
	 * View edit
	 *
	 * @var array
	 */
	public $view_edit = ['string','timestamp'];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_get = ['id','string','timestamp'];

	/**
	 * View search
	 *
	 * @var array
	 */
	public $view_search = ['id','string','timestamp'];

}

?>