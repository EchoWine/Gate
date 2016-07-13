<?php

namespace Admin\Middleware;

use CoreWine\Request;
use CoreWine\Router;
use CoreWine\Middleware;
use Auth\Service\Auth;

class Authenticate extends Middleware{

	/**
	 * Handle
	 */
	public function handle(){

		if(!Auth::logged())
			Request::redirect(Router::url('admin/login'));

	}

}

?>