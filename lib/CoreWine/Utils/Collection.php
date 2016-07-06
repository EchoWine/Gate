<?php

namespace CoreWine\Utils;

use ArrayAccess;

class Collection implements ArrayAccess{

    protected $container = [];

    public function __construct($values = []){
        foreach($values as $value){
            $this -> container[] = $value;
        }
    }

    public function offsetSet($offset, $value){
        if(is_null($offset)){
            $this -> container[] = $value;
        }else{
            $this -> container[$offset] = $value;
        }
    }

    public function offsetExists($offset){
        return isset($this -> container[$offset]);
    }

    public function offsetUnset($offset){
        unset($this -> container[$offset]);
    }

    public function offsetGet($offset){
        return isset($this -> container[$offset]) ? $this -> container[$offset] : null;
    }

}