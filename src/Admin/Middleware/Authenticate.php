<?php

namespace Admin\Middleware;

use CoreWine\Http\Request;
use CoreWine\Http\Router;
use CoreWine\Http\Middleware;
use Auth\Service\Auth;

class Authenticate extends Middleware{

	/**
	 * Handle
	 */
	public function handle(){

		if(!Auth::logged()){
			Request::redirect(Router::url('admin/login'));
		}

	}

}

?>