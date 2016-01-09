<?php

class stdResponse{

	/**
	 * Title
	 */
	public $title;

	/**
	 * Type of message
	 */
	public $type;

	/**
	 * Message
	 */
	public $message = [];

	/**
	 * Construct
	 *
	 * @param int $type type of messages [0-3]
	 * @param string $title title of message
	 * @param mixed $message array or a single text that contains the message
	 */
	public function __construct($type,$title,$message = null){
		$this -> title = $title;
		$this -> type = $type;

		if($message !== null)
			$this -> message = is_array($message) && count($message) == 1 ? $message[0] : $message;
		
	}

	/**
	 * Add a message
	 *
	 * @param string $message message
	 */
	public function addMessage($message){
		$this -> message[] = $message;

	}


	/**
	 * Get count of messages
	 *
	 * @return int count
	 */
	public function getCount(){
		return is_array($this -> message) ? count($this -> message) : 1;
	}
}

?>