<?php

namespace Serie\Api\TheTVDB;

class EpisodeObject{

	/**
	 * @var integer
	 **/
	protected $id;

	protected $name;

	protected $number;

	protected $season;

	protected $overview;

	protected $rating;

	protected $rating_count;

	protected $aired_at;

	protected $updated_at;

	/**
	 * Initialize the object with the response in xml
	 *
	 * @param XML object
	 */
	public function __construct($resource){

		$this -> id = (int)$resource -> id;
		$this -> name = (string)$resource -> EpisodeName;
		$this -> overview = (string)$resource -> Overview;
		$this -> number = (string)$resource -> EpisodeNumber;
		$this -> season = (string)$resource -> SeasonNumber;

		$this -> rating = (float)$resource -> Rating;
		$this -> rating_count = (int)$resource -> RatingCount;

		$this -> aired_at = (string)$resource -> FirstAired;
		$this -> updated_at = (string)$resource -> lastupdated;

	}

}