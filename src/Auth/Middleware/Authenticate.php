<?php

namespace Auth\Middleware;

use CoreWine\Request;
use CoreWine\Router;
use CoreWine\Middleware;
use Auth\Service\Auth;

class Authenticate extends Middleware{

	/**
	 * Handle
	 */
	public function handle(){

		Auth::load();

	}

}

?>