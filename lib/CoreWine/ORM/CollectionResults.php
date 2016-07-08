<?php

namespace CoreWine\ORM;

use CoreWine\Utils\Collection;

class CollectionResults extends Collection{

	
	public $repository;

    public function toArray(){
        $return = [];
        foreach($this -> container as $item){
            $return[] = $item -> toArray();
        }

        return $return;
    }

    public function setRepository($repository){
    	$this -> repository = $repository;
    }

    public function getRepository(){
    	return $this -> repository;
    }

    public function getPagination(){
    	return $this -> getRepository() -> getPagination();
    }
}