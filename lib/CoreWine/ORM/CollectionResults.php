<?php

namespace CoreWine\ORM;

use CoreWine\Utils\Collection;

class CollectionResults extends Collection{

    public function toArray(){
        $return = [];
        foreach($this -> container as $item){
            $return[] = $item -> toArray();
        }

        return $return;
    }
}