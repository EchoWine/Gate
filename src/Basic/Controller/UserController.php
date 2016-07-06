<?php

namespace Basic\Controller;

use Admin\Controller\AdminController;


class UserController extends AdminController{

	public $__entity = 'Auth\Model\User';

	public $url = 'user';

	public $view_list = ['id','username','password','email'];
	public $view_edit = ['id','username','password','email'];
	public $view_add = ['id','username','password','email'];
	public $view_get = ['id','username','password','email'];

	public function index(){
	
		return parent::index();
	}
}

?>