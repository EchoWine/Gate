<?php

namespace CoreWine\Component;

use Iterator;
use ArrayAccess;

class Collection implements Iterator,ArrayAccess{

    protected $container = [];

    public function __construct($array = []){
        if(is_array($array)){
            $this -> container = $array;
        }
    }

    public function rewind(){
        reset($this -> container);
    }
  
    public function current(){
        $container = current($this -> container);
        return $container;
    }
  
    public function key(){
        $container = key($this -> container);
        return $container;
    }
  
    public function next(){
        $container = next($this -> container);
        return $container;
    }
  
    public function valid(){
        $key = key($this -> container);
        $container = ($key !== NULL && $key !== FALSE);
        return $container;
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