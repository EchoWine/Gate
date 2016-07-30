<?php

namespace CoreWine\Http\Response;
use CoreWine\Http\Response\Response as Response;

class RedirectResponse extends Response{


	public function __construct() {
		parent::__construct();
		$this -> status_code = Response::HTTP_PERMANENTLY_REDIRECT;
	}

	/**
	 * Redirects to the specified URL
	 *
	 * @param string $url 				The redirecting url
	 * @param boolean $temporary 		Specifies whether the redirection is temporary
	 * @return \CoreWine\Http\Response\RedirectResponse 
	 */
	public function to($url, $temporary = false) {
		// @todo check url validity
		if ($temporary === true) {
			$this -> status_code = Response::HTTP_TEMPORARY_REDIRECT;
		}
		$this -> header('Location', $url); 

		return $this;
	}

	/**
	 * Redirects back (there's no warranty it will work)
	 *
	 * @return  \CoreWine\Http\Response\RedirectResponse				
	 */
	public function back() {
		$this -> header('Location', 'javascript://history.go(-1)');

		return $this;
	}




}