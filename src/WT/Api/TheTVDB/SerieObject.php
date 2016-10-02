<?php

namespace WT\Api\TheTVDB;

class SerieObject{

	/**
	 * @var integer
	 **/
	public $id;

	public $name;

	public $actors;

	public $airs_day_of_week;

	public $airs_time;

	public $genres = [];

	public $language;

	public $network;

	public $overview;

	public $rating;

	public $rating_count;

	public $status;

	public $banner;

	public $fanart;

	public $poster;

	public $first_aired_at;

	public $updated_at;

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
		$this -> genres = explode("|",$this -> genres);

		foreach($resource -> Episode as $episode){
			$this -> episodes[] = new EpisodeObject($episode);
		}

	}

}