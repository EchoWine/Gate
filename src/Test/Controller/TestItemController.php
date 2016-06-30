<?php

namespace Test\Controller;

use CoreWine\SourceManager\Controller as Controller;
use Test\Entity\Hodor;

class TestItemController extends Controller{
	
	/**
	 * Set all Routers
	 */
	public function __routes(){

		$this -> route('index') -> url("/test/item");

	}

	/**
	 * Set index
	 */
	public function index(){
		$data = [
			'foo' => 'fee'
		];

		$user = Hodor::create($data);

		print_r("ID: ".$user -> id);
		print_r("<br>\n");
		print_r("Foo: ".$user -> foo);
		die();
	}

}

?>