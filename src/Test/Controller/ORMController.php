<?php

namespace Test\Controller;

use CoreWine\DataBase\DB;
use CoreWine\SourceManager\Controller as Controller;

use Test\Model\Hodor;
use Test\Model\Serie;
use Test\Model\Episode;

class ORMController extends Controller{
	
	/**
	 * Set all Routers
	 */
	public function __routes(){

		$this -> route('basic') -> url("/test/ORM/basic");
		$this -> route('relation') -> url("/test/ORM/relation");

	}
		
	/**
	 * @Route
	 */
	public function relation(){

		DB::clearLog();

		# New Model
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

		# New Model
		$hodor = new Hodor();

		# Alias new Model
		$hodor = Hodor::new();

		# Defining an attribute
		$hodor -> door = 'Hold the door';

		# Fill Model with array of attributes
		$hodor -> fill(['door' => 'Hold the door']);

		# Save changes
		$hodor -> save();

		# Get ID
		$hodor -> id;

		# New Model and save in one method
		$hodor = Hodor::create(['door' => "I'm busy"]);

		# Search Model
		$hodor = Hodor::where('id',1) -> first();
		$hodor -> door = 'Rekt';
		$hodor -> save();

		# Get Schema
		$hodor -> door() -> getSchema() -> getMaxLength();
		$hodor -> getField('door') -> getSchema() -> getMaxLength();
		$hodor -> getSchema() -> getField('door') -> getMaxLength();

		# Get last validation failed during saving an Model
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