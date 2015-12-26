<?php

class stdResponse{

	public $title;

	public $type;

	public $message;

	public $count;

	public function __construct($type,$title,$message){
		$this -> title = $title;
		$this -> type = $type;
		$this -> count = is_array($message) ? count($message) : 1;
		$this -> message = is_array($message) && $this -> count == 1 ? $message[0] : $message;
	}
}

?>