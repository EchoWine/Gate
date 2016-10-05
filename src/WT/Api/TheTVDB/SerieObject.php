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
	public static function long($resource){

		if(isset($resource -> Series[0]))
			$serie = $resource -> Series[0];
		else
			$serie = $resource;

		$obj = new self();

		$obj -> id = $serie -> id;
		$obj -> name = $serie -> SeriesName;

		$obj -> airs_day_of_week = $serie -> Airs_DayOfWeek;
		$obj -> airs_time = $serie -> Airs_Time;
		$obj -> language = $serie -> Language;
		$obj -> network = $serie -> Network;
		$obj -> overview = $serie -> Overview;
		$obj -> rating = $serie -> Rating;
		$obj -> rating_count = $serie -> RatingCount;
		$obj -> status = $serie -> Status;
		$obj -> banner = $serie -> banner;
		$obj -> fanart = $serie -> fanart;
		$obj -> poster = $serie -> poster;
		$obj -> first_aired_at = $serie -> FirstAired;

		$obj -> updated_at = $serie -> lastupdated;

		# Temp
		$obj -> actors = $serie -> Actors;
		$obj -> genres = $serie -> Genre;
		$obj -> genres = explode("|",$obj -> genres);

		foreach($resource -> Episode as $episode){
			$obj -> episodes[] = new EpisodeObject($episode);
		}

		return $obj;

	}

	/**
	 * Initialize the object with the response in xml
	 *
	 * @param XML object
	 */
	public static function short($resource){

		$serie = $resource;


		$obj = new self();

		$obj -> id = $serie -> id;
		$obj -> name = $serie -> SeriesName;
		
		if(isset($serie -> Language))
			$obj -> language = $serie -> Language;
		
		if(isset($serie -> Network))
			$obj -> network = $serie -> Network;

		if(isset($serie -> Overview))
			$obj -> overview = $serie -> Overview;
	
		$obj -> first_aired_at = $serie -> FirstAired;


		return $obj;
	}


}