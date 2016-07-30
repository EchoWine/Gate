<?php

namespace Admin\View\Component;

class Select{

	public $value;
	public $label;

	public function __construct($url,$value,$label = null,$search = null){
		$this -> url = $url;
		$this -> value = $value;
		$this -> label = ($label == null) ? $value : $label;

		if($search == null){
			$search = $label;
			$search = preg_replace("/^([^\{]*)\{/iU","'$1';",$search);
			$search = preg_replace("/}([^\}]*)$/iU",";'$1'",$search);
			$search = preg_replace("/\}([^\{]*)\{/iU",";'$1';",$search);
			$search = preg_replace("/\}/iU",";'",$search);
			$search = preg_replace("/\{/iU","';",$search);
			$search = preg_replace("/;''/iU","",$search);
			$search = preg_replace("/'';/iU","",$search);
		}

		$this -> search = $search;
	}

	public function getValue(){
		return $this -> value;
	}

	public function getLabel(){
		return $this -> label;
	}

	public function getUrl(){
		return $this -> url;
	}

	public function getSearch(){
		return $this -> search;
	}
}
?>