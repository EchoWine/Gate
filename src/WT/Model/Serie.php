<?php

namespace WT\Model;

use CoreWine\DataBase\ORM\Model;

class Serie extends Model{

	/**
	 * Table name
	 *
	 * @var
	 */
	public static $table = 'series';

	/**
	 * Set schema fields
	 *
	 * @param Schema $schema
	 */
	public static function fields($schema){

		$schema -> id();
		
		$schema -> string('name');

		$schema -> string('genres');

		$schema -> text('overview');

		$schema -> string('status');

		$schema -> file('poster');

		$schema -> file('banner');

		$schema -> toOne(Resource::class,'resource');

		$schema -> toMany(Season::class,'seasons','serie_id');

		$schema -> toMany(Episode::class,'episodes','serie_id');

	}

	public function toArray(){

		$res = parent::toArray();

		$res['poster'] = $this -> poster() -> getFullPath();
		$res['banner'] = $this -> banner() -> getFullPath();

		foreach(Episode::where('serie_id',$this -> id) -> get() as $episode){
			$episodes[] = $episode -> toArray();
		}

		return array_merge($res,['episodes' => $episodes]);
	}
}

?>