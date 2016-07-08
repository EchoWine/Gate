<?php

namespace CoreWine\TemplateEngine;

use CoreWine\Response\Response as BasicResponse;

class Response extends BasicResponse{

	/**
	 * Set content
	 */
	public function sendBody(){

		foreach($GLOBALS as $n => $k){
			$$n = $k;
		}

		$s = Engine::startRoot();
		include $this -> getBody();
		Engine::endRoot();
	}
}