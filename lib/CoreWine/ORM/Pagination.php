<?php

namespace CoreWine\ORM;

class Pagination{

	public $count;
	public $page;
	public $pages;
	public $from;
	public $to;

	public function __construct(){

	}

	public function toArray(){
		return [
			'count' => $this -> count,
			'page' => $this -> page,
			'pages' => $this -> pages,
			'from' => $this -> from,
			'to' => $this -> to
		];
	}

}

?>