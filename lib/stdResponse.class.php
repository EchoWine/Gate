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
	 * @param $type (int) type of messages [0-3]
	 * @param $title (string) title of message
	 * @param $message (mixed) array or a single text that contains the message
	 */
	public function __construct($type,$title,$message = null){
		$this -> title = $title;
		$this -> type = $type;

		if($message !== null)
			$this -> message = is_array($message) && count($message) == 1 ? $message[0] : $message;
		
	}

	/**
	 * Add a message
	 * @param $message (string) message
	 */
	public function addMessage($message){
		$this -> message[] = $message;

	}


	/**
	 * Get count of messages
	 * @return (int) count
	 */
	public function getCount(){
		return is_array($this -> message) ? count($this -> message) : 1;
	}
}

?>