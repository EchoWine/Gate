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
		
	public function printLog(){

		$mex = '';
		foreach(DB::log(true) as $k){
			$mex .= $k."\n<br>";
		}
		$this -> print($mex);
	}


	public function print($mex){

		echo "<div style='border:1px solid #efefef;padding:5px;margin:10px 0;font-size:12px'>";
		echo $mex;
		echo "</div>";
	}


	/**
	 * @Route
	 */
	public function relation(){


		$time = microtime(true);
		
		Episode::truncate();
		Serie::truncate();
		DB::clearLog();
		
		# New serie
		# Once the serie is saved into database, will retrieve automatically the primary key (id)

 		$got = new Serie();
		$got -> name = 'Game of Thrones';

		echo "New serie: Game of Thrones";
		$this -> print($got);

		$got -> save();

		echo "Save";
		$this -> printLog();

		echo "Getting ID";
		$this -> print($got);

		$pof = Serie::create(['name' => 'Person of Interest']);

		echo "Create a new serie Person of Interest";
		$this -> printLog();

		$this -> print($pof);

		$ep = Episode::create(['name' => 'episode 1','serie' => $got]);

		echo "Create a new episode in Game of Thrones";
		$this -> printLog();
		$this -> print($ep);

		$ep -> serie_id = $pof -> id;
		echo "Updated serie of episode";
		$this -> print($ep -> serie_id);
		$this -> print($ep);
		$this -> print($ep -> serie); # Query to retrieve new Serie execute in this moment
		$this -> printLog();

		echo "Saved episode";
		$ep -> save();
		$this -> printLog();





		$ep2 = Episode::create(['name' => 'episode 2','serie' => $got]);
		$ep2 -> prev = $ep;
		$ep2 -> save();
		$ep -> next = $ep2;
		$ep -> save();
		$ep -> id;

		DB::log();

		$ep2 = Episode::where('name','episode 2') -> first();
		$ep = Episode::where('name','episode 1') -> first();

		$ep2 -> serie -> name = 'Trono di spade';
		$ep -> next -> prev -> name = "Close the door";
		echo $ep -> serie -> name;
		echo "<br>";
		echo $ep -> name;
		$ep -> save();
		echo "<br><br>";

		/*
		# Select all series and only the episodes with name 'episode 1'
		$got = Serie::with(['episodes' => function($q){
			return $q -> where('name','episode 1');
		}]) -> first();

		# Select all series that have an episode with name 'episode 1'
		$got = Serie::has(['episodes' => function($q){
			return $q -> where('name','episode 1');
		}]) -> first();
		

		$episodes = Episode::where('name','episode 1') -> get();
		$series = [];
		foreach($episodes as $episode)
			$series[] = $episode -> serie


		# Select all series that have an episode with name 'episode 1' and select only the episodes with name 'episode 1'
		$got = Serie::only(['episodes' => function($q){
			return $q -> where('name','episode 1');
		}]) -> first();

		# Select all series that have at least 10 episode
		$got = Serie::has(['episodes' => function($q){
			return $q -> count() > 10;
		}]) -> first();
		*/

		$got = Serie::first();

		foreach($got -> episodes as $episode){
			echo $episode -> name;
			echo "<br>";
		}

		$ep3 = new Episode();
		$ep3 -> name = "Winter is coming";

		$got -> episodes() -> add($ep3);
		$got -> episodes() -> remove($ep2);
		$got -> episodes() -> save();

		foreach($got -> episodes as $episode){
			echo $episode -> name;
			echo "<br>";
		}

		$serie = Serie::copy($got);

		foreach(Episode::all() as $ep){
			if($ep -> serie){
				//echo $ep -> serie -> id;
			}
		}

		echo microtime(true) - $time;
		

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