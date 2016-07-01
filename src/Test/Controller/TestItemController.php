<?php

namespace Test\Controller;

use CoreWine\DataBase\DB;
use CoreWine\SourceManager\Controller as Controller;
use Test\Entity\Serie;
use Test\Entity\Episode;

class TestItemController extends Controller{
	
	/**
	 * Set all Routers
	 */
	public function __routes(){

		$this -> route('basic') -> url("/test/item/basic");
		$this -> route('relation') -> url("/test/item/relation");

	}
		
	/**
	 * @Route
	 */
	public function relation(){

		DB::clearLog();

		# New Entity
		$got = new Serie();
		$got -> name = 'Game of Thrones';
		$got -> save();


		$ep = new Episode();
		$ep -> name = 'Hold the Door';
		$ep -> serie = $got;
		$ep -> serie_id = $got -> id;
		$ep -> save();

		$got -> delete();

		DB::printLog();
		die();



	}

	/**
	 * @Route
	 */
	public function basic(){

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
		$hodor -> door = 'awass';
		$hodor -> save();
		$hodor -> delete();
		$hodor -> save();


		# Create a copy
		$hodor = Hodor::copy($hodor);
		$hodor -> delete();

		DB::printLog();

		die();


	}


}

?>