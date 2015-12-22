<?php

class stdObject{

	public function __construct(array $arguments = array()){
		if(!empty($arguments)){
			foreach($arguments as $property => $argument){
				if($argument instanceOf Closure){
					$this->{$property} = $argument;
				}else{
					$this->{$property} = $argument;
				}
			}
		}
	}

	public function __call($method, $arguments) {
		if(isset($this->{$method}) && is_callable($this->{$method})) {
			return call_user_func_array($this->{$method}, $arguments);
		}else{
			throw new Exception("Fatal error: Call to undefined method stdObject::{$method}()");
		}
	}
}

?>