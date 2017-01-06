<?php

namespace Kernel\Exceptions;

class Exception extends \Exception{

	protected $message;
	private $string;
	protected $code;
	protected $file;
	protected $line;
	protected $detail;
	protected $trace;

	public function __construct($message = null,$detail = '',$code = 0,$file = null,$line = null,$trace = null){

		if(!$this -> message)
			$this -> message = $message;

		if(!$this -> message)
			throw new $this('Unknown '. get_class($this));

		$this -> detail = $detail;
		$this -> code = $code;
		$this -> file = $file;
		$this -> line = $line;
		$this -> trace = $trace;

		parent::__construct($this -> message,$this -> code);
	}

	public function __toString(){
		return get_class($this)."'{$this->message}' in {$this->file}({$this->line})\n{$this->getTraceAsString()}";
	}

	public function getDetail(){
		return $this -> detail;
	}

	public function setFile($file){
		if(!empty($file))
			$this -> file = $file;
	}

	public function setTrace($trace){
		$this -> trace = $trace;
	}

	public function setLine($line){
		$this -> line = $line;
	}

	public function setClass($class){
		$this -> class = $class;
	}


	public function getClass(){
		return $this -> class;
	}

	public static function cloneFrom($e){
		$t = new static($e -> getMessage());
		$t -> setClass(get_class($e));
		$t -> setFile($e -> getFile());
		$t -> setLine($e -> getLine());
		$t -> setTrace(array_merge([
			'file' => $t -> getFile(),
			'line' => $t -> getLine()
		],$e -> getTrace()));


		return $t;
	}
}
?>