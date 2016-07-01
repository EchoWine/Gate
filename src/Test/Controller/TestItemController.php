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

		# Create a new Entity
		$hodor = new Hodor();
		$hodor = Hodor::new();
		$hodor -> door = 'Hold the door';
		$hodor -> fill(['door' => 'Hold the door']);
		$hodor -> save();
		$hodor -> id; # Field AutoIncrement accessible
		$hodor = Hodor::create(['door' => "I'm busy"]);
		

		# Search entity
		$hodor = Hodor::where('id',1) -> first();
		$hodor -> door = 'Rekt';
		$hodor -> save();


		# Get Schema
		$hodor -> door() -> getSchema() -> getMaxLength();
		$hodor -> getField('door') -> getSchema() -> getMaxLength();
		$hodor -> getSchema() -> getField('door') -> getMaxLength();

		# Get last validation failed during saving an entity
		$hodor -> door = 'to'; # Too short
		$hodor -> save();
		print_r(Hodor::getLastValidate());


		die();

		$this -> create(Hodor::class);
		$this -> create(Hodor::class,[
			'door' => '?'
		]);

		$this -> create(Hodor::class,[
			'door' => 'Hold the door'
		]);
		die();
	}

	public function create($class,$data = []){

		print_r("Trying to create with data: <br>\n");
		print_r($data);
		$user = Hodor::create($data);

		if(!$user){
			$validate = Hodor::getLastValidate();
			print_r("\n\n<br><br>Error:\n<br>");
			print_r($validate);
		}else{
			print_r("\n\n<br><br>");
			print_r("ID: ".$user -> id."<br>\n");
			print_r("Door: ".$user -> door);
		}

		print_r("\n\n<br><br>----------------------------------------------------------------------------\n<br>\n\n<br>");
		

	}

}

?>