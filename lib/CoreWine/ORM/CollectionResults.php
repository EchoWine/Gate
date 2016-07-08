<?php

namespace CoreWine\ORM;

use CoreWine\Utils\Collection;

class CollectionResults extends Collection{

	/**
	 * Repository
	 *
	 * @var ORM\Repository
	 */
	public $repository;

    public function toArray(){
        $return = [];
        foreach($this -> container as $item){
            $return[] = $item -> toArray();
        }

        return $return;
    }

	/**
	 * Set repository
	 *
	 * @param ORM\Repository $repository
	 */
    public function setRepository($repository){
    	$this -> repository = $repository;
    }

	/**
	 * Get repository
	 *
	 * @return ORM\Repository
	 */
    public function getRepository(){
    	return $this -> repository;
    }

    /**
     * Get Pagination
     *
     * @return ORM\Pagination
     */
    public function getPagination(){
    	return $this -> getRepository() -> getPagination();
    }
}