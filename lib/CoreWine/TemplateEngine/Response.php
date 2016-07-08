<?php

namespace CoreWine\TemplateEngine;

use CoreWine\Response\Response as BasicResponse;

class Response extends BasicResponse{

	/**
	 * Set content
	 */
	public function sendContent(){

		foreach($GLOBALS as $n => $k){
			$$n = $k;
		}

		$s = Engine::startRoot();
		include $this -> getContent();
		Engine::endRoot();
	}
}