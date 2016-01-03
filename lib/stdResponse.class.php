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
	 * Number of message
	 */
	public $count = 0;

	/**
	 * Construct
	 * @param $type (int) type of messages [0-3]
	 * @param $title (string) title of message
	 * @param $message (mixed) array or a single text that contains the message
	 */
	public function __construct($type,$title,$message = null){
		$this -> title = $title;
		$this -> type = $type;

		if($message !== null){

			$this -> count = is_array($message) ? count($message) : 1;
			$this -> message = is_array($message) && $this -> count == 1 ? $message[0] : $message;
		}
	}

	/**
	 * Add a message
	 * @param $message (string) message
	 */
	public function addMessage($message){
		$this -> message[] = $message;
		$this -> count++;
	}
}

?>