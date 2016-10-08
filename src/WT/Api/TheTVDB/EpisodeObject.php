<?php

namespace WT\Api\TheTVDB;

use WT\Api\Object;

class EpisodeObject extends Object{

	/**
	 * Initialize the object with the response in xml
	 *
	 * @param XML object
	 */
	public function __construct($resource){

		
		$this -> id = $resource -> id;
		$this -> name = $resource -> EpisodeName;
		$this -> overview = $resource -> Overview;
		$this -> number = $resource -> EpisodeNumber;
		$this -> season = $resource -> SeasonNumber;

		$this -> rating = $resource -> Rating;
		$this -> rating_count = $resource -> RatingCount;

		$this -> aired_at = $resource -> FirstAired;
		$this -> updated_at = $resource -> lastupdated;

	}

}