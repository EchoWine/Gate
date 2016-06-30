<?php

namespace Basic\Controller;

use Admin\Controller\AdminController;
use Basic\Entity\User;

class UserController extends AdminController{

	public $__entity = 'Basic\Entity\User';

	public $url = 'user';

	public function index(){
		

		return parent::index();


	}
}

?>