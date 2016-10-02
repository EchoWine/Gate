<?php

namespace WT\Api\TheTVDB;

class SerieObject{

	/**
	 * @var integer
	 **/
	protected $id;

	protected $name;

	protected $actors;

	protected $airs_day_of_week;

	protected $airs_time;

	protected $genres = [];

	protected $language;

	protected $network;

	protected $overview;

	protected $rating;

	protected $rating_count;

	protected $status;

	protected $banner;

	protected $fanart;

	protected $poster;

	protected $first_aired_at;

	protected $updated_at;

	/**
	 * Initialize the object with the response in xml
	 *
	 * @param XML object
	 */
	public function __construct($resource){

		$this -> id = (int)$resource -> Series[0] -> id;
		$this -> name = (string)$resource -> Series[0] -> SeriesName;

		$this -> airs_day_of_week = (string)$resource -> Series[0] -> Airs_DayOfWeek;
		$this -> airs_time = (string)$resource -> Series[0] -> Airs_Time;
		$this -> language = (string)$resource -> Series[0] -> Language;
		$this -> network = (string)$resource -> Series[0] -> Network;
		$this -> overview = (string)$resource -> Series[0] -> Overview;
		$this -> rating = (float)$resource -> Series[0] -> Rating;
		$this -> rating_count = (int)$resource -> Series[0] -> RatingCount;
		$this -> status = (string)$resource -> Series[0] -> Status;
		$this -> banner = (string)$resource -> Series[0] -> banner;
		$this -> fanart = (string)$resource -> Series[0] -> fanart;
		$this -> poster = (string)$resource -> Series[0] -> poster;
		$this -> first_aired_at = (string)$resource -> Series[0] -> FirstAired;

		$this -> updated_at = (string)$resource -> Series[0] -> lastupdated;

		# Temp
		$this -> actors = (string)$resource -> Series[0] -> Actors;
		$this -> genres = (string)$resource -> Series[0] -> Genre;


		foreach($resource -> Episode as $episode){
			$this -> episode[] = new EpisodeObject($episode);
		}

	}

}