<?php

namespace Basic\Controller;

use Admin\Controller\AdminController;


class UserController extends AdminController{

	public $__entity = 'Auth\Model\User';

	public $url = 'user';

	public function index(){
	
		return parent::index();
	}
}

?>