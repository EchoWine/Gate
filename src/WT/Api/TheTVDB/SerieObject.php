<?php

namespace WT\Api\TheTVDB;

use WT\Api\Object;

class SerieObject extends Object{


	/**
	 * Initialize the object with the response in xml
	 *
	 * @param XML object
	 */
	public static function long($resource){


		$serie = $resource -> Series;
	
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

		switch($serie -> Status){
			case 'Continuing':
				$obj -> status = 'continuing';
			break;
			case 'Ended':
				$obj -> status = 'ended';
			break;
		}
		$obj -> banner = $serie -> banner;
		$obj -> fanart = $serie -> fanart;
		$obj -> poster = $serie -> poster;


		if(isset($serie -> FirstAired)){
			$obj -> first_aired_at = $serie -> FirstAired;
		}

		$obj -> updated_at = $serie -> lastupdated;

		# Temp
		$obj -> actors = $serie -> Actors;
		$obj -> genres = $serie -> Genre;
		$obj -> genres = explode("|",$obj -> genres);

		$obj -> episodes = [];
		
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
		
		if(isset($serie -> FirstAired))
			$obj -> first_aired_at = $serie -> FirstAired;


		return $obj;
	}


}