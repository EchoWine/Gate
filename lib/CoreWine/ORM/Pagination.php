<?php

namespace CoreWine\ORM;

class Pagination{

	/**
	 * Count
	 *
	 * @var int
	 */
	public $count;

	/**
	 * Show
	 *
	 * @var int
	 */
	public $show;

	/**
	 * Page
	 *
	 * @var int
	 */
	public $page;

	/**
	 * Pages
	 *
	 * @var int
	 */
	public $pages;

	/**
	 * From
	 *
	 * @var int
	 */
	public $from;

	/**
	 * To
	 *
	 * @var int
	 */
	public $to;

	public function __construct($count,$show,$page){


		if($show < 1)
			$show = 1;

		# Calculate pages
		$pages = ceil($count / $show);

		if($page !== 1){

			if($page < 1)
				$page = 1;

			if($page > $pages)
				$page = $pages;
			
			$skip = ($page - 1) * $show;

		}else{
			$skip = 0;
		}

		$this -> setCount($count);
		$this -> setShow($show);
		$this -> setPage($page);
		$this -> setPages($pages);
		$this -> setSkip($skip);
		$this -> setFrom($skip + 1);
		$this -> setTo($skip + $show);


	}

	public function setShow($show){
		$this -> show = $show;
	}

	public function getShow(){
		return $this -> show;
	}

	public function setCount($count){
		$this -> count = $count;
	}

	public function getCount(){
		return $this -> count;
	}

	public function setPage($page){
		$this -> page = $page;
	}

	public function getPage(){
		return $this -> page;
	}

	public function setPages($pages){
		$this -> pages = $pages;
	}

	public function getPages(){
		return $this -> pages;
	}

	public function setFrom($from){
		$this -> from = $from;
	}

	public function getFrom(){
		return $this -> from;
	}

	public function setTo($to){
		$this -> to = $to;
	}

	public function getTo(){
		return $this -> to;
	}


	public function setSkip($skip){
		$this -> skip = $skip;
	}

	public function getSkip(){
		return $this -> skip;
	}

	public function toArray(){
		return [
			'count' => $this -> getCount(),
			'page' => $this -> getPage(),
			'pages' => $this -> getPages(),
			'from' => $this -> getFrom(),
			'to' => $this -> getTo()
		];
	}

}

?>