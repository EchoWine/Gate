<?php

namespace CoreWine\ORM;

use CoreWine\Component\Collection;

class CollectionResults extends Collection{

	/**
	 * Repository
	 *
	 * @var ORM\Repository
	 */
	public $repository;

    /**
     * Convert collection into array
     *
     * @return array
     */
    public function toArray(){
        $return = [];
        foreach($this -> container as $item){
            if($item instanceof Model){
                $return[] = $item -> toArray();
            }else{
                $return[] = $item;
            }
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