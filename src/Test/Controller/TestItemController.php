<?php

namespace Test\Controller;

use CoreWine\DataBase\DB;
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

		DB::clearLog();

		# New Entity
		$hodor = new Hodor();

		# Alias new entity
		$hodor = Hodor::new();

		# Defining an attribute
		$hodor -> door = 'Hold the door';

		# Fill entity with array of attributes
		$hodor -> fill(['door' => 'Hold the door']);

		# Save changes
		$hodor -> save();

		# Get ID
		$hodor -> id;

		# New entity and save in one method
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

		# Get array of last validation
		Hodor::getLastValidate();

		# Delete
		$hodor = new Hodor();
		$hodor -> door = 'awa';
		$hodor -> save();
		$hodor -> delete();

		DB::printLog();

		die();

		


	}


}

?>