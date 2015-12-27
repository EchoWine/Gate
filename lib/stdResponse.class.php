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
	public $message;

	/**
	 * Number of message
	 */
	public $count;

	/**
	 * Construct
	 * @param $type (int) type of messages [0-3]
	 * @param $title (string) title of message
	 * @param $message (mixed) array or a single text that contains the message
	 */
	public function __construct($type,$title,$message){
		$this -> title = $title;
		$this -> type = $type;
		$this -> count = is_array($message) ? count($message) : 1;
		$this -> message = is_array($message) && $this -> count == 1 ? $message[0] : $message;
	}
}

?>