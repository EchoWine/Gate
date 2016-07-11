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

	/**
	 * Construct
	 *
	 * @param int $count
	 * @param int $show
	 * @param int $page
	 */
	public function __construct($count,$show,$page){


		if($show < 1)
			$show = 1;

		# Calculate pages
		$pages = ceil($count / $show);

		$skip = 0;

		if($page !== 1){

			if($page < 1)
				$page = 1;

			if($page > $pages)
				$page = $pages;
			
			$skip = ($page - 1) * $show;

		}

		if($skip < 0)
			$skip = 0;


		$this -> setCount($count);
		$this -> setShow($show);
		$this -> setPage($page);
		$this -> setPages($pages);
		$this -> setSkip($skip);
		$this -> setFrom($skip + 1);
		$this -> setTo($skip + $show);


	}

	/**
	 * Set show
	 *
	 * @param int $show
	 */
	public function setShow($show){
		$this -> show = $show;
	}

	/**
	 * Get show
	 *
	 * @return int
	 */
	public function getShow(){
		return $this -> show;
	}

	/**
	 * Set count
	 *
	 * @param int $count
	 */
	public function setCount($count){
		$this -> count = $count;
	}

	/**
	 * Get count
	 *
	 * @return int
	 */
	public function getCount(){
		return $this -> count;
	}

	/**
	 * Set page
	 *
	 * @param int $page
	 */
	public function setPage($page){
		$this -> page = $page;
	}

	/**
	 * Get page
	 *
	 * @return int
	 */
	public function getPage(){
		return $this -> page;
	}

	/**
	 * Set pages
	 *
	 * @param int $pages
	 */
	public function setPages($pages){
		$this -> pages = $pages;
	}

	/**
	 * Get pages
	 *
	 * @return int
	 */
	public function getPages(){
		return $this -> pages;
	}

	/**
	 * Set from
	 *
	 * @param int $from
	 */
	public function setFrom($from){
		$this -> from = $from;
	}

	/**
	 * Get from
	 *
	 * @return int
	 */
	public function getFrom(){
		return $this -> from;
	}

	/**
	 * Set to
	 *
	 * @param int $to
	 */
	public function setTo($to){
		$this -> to = $to;
	}

	/**
	 * Get to
	 *
	 * @return int
	 */
	public function getTo(){
		return $this -> to;
	}

	/**
	 * Set skip
	 *
	 * @param int $skip
	 */
	public function setSkip($skip){
		$this -> skip = $skip;
	}

	/**
	 * Get skip
	 *
	 * @return int
	 */
	public function getSkip(){
		return $this -> skip;
	}

	/**
	 * Returns array representation of object
	 *
	 * @return Array
	 */
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