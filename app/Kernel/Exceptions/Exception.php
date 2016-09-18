<?php

namespace Kernel\Exceptions;

class Exception extends \Exception{

	protected $message;
	private $string;
	protected $code;
	protected $file;
	protected $line;
	protected $detail;
	private $trace;

	public function __construct($message = null,$detail = '',$code = 0,$file = null,$line = null){

		if(!$this -> message)
			$this -> message = $message;

		if(!$this -> message)
			throw new $this('Unknown '. get_class($this));

		$this -> detail = $detail;
		$this -> code = $code;
		$this -> file = $file;
		$this -> line = $line;

		parent::__construct($this -> message,$this -> code);
	}

	public function __toString(){
		return get_class($this)."'{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}";
	}

	public function getDetail(){
		return $this -> detail;
	}
}
?>