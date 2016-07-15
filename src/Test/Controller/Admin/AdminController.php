<?php

namespace Test\Controller\Admin;

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
	public $view_list = ['id','string','timestamp','text'];

	/**
	 * View add
	 *
	 * @var array
	 */
	public $view_add = ['string','timestamp','text'];

	/**
	 * View edit
	 *
	 * @var array
	 */
	public $view_edit = ['string','timestamp','text'];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_get = ['id','string','timestamp','text'];

	/**
	 * View search
	 *
	 * @var array
	 */
	public $view_search = ['id','string','timestamp','text'];

}

?>