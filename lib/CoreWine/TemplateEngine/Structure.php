<?php


namespace CoreWine\TemplateEngine;

/**
 * Element of structure page
 */
class Structure{

	public $name;
	public $type;
	public $childs = [];
	public $parent;
	public $prev;
	public $next;
	public $inner;
	public $content = '';
	public $overwrite = true;
	public $source;

	public function __construct($name,$type){
		$this -> name = $name;
		$this -> type = $type;
	}


	public function getName(){
		return $this -> name;
	}

	public function getType(){
		return $this -> type;
	}
	
	public function addChild($structure){
		$child = $this -> getLastChild();
		if($child != null){
			$child -> next = $structure;
			$structure -> prev = $child;
		}
		$this -> childs[$structure -> name] = $structure;

	}

	public function findChildByName($name){
		foreach($this -> childs as $child){
			if($child -> name == $name)return $child;
		}
		return null;
	}

	public function setSource($source){
		$this -> source = $source;
	}

	public function getSource(){
		return $this -> source;
	}

	public function getLastChild(){
		return end($this -> childs);
	}

	public function getNext(){
		return $this -> next;
	}

	public function getNextOrParent(){
		return $this -> next ? $this -> next : $this -> getParent();
	}

	public function setInner($structure){
		$this -> inner = $structure;
	}

	public function getInner(){
		return $this -> inner;
	}

	public function getPrev(){
		return $this -> prev;
	}

	public function setParent($structure){
		$this -> parent = $structure;
	}

	public function getParent(){
		return $this -> parent;
	}


	public function setContent($content){
		$this -> content = $content;
	}


	public function getContent(){
		return $this -> content;
	}

	public function concatContent($content){
		$this -> content .= $content;
	}

	public function removeStructure($index){
		unset($this -> elements[$index]);
	}

	public function setOverwrite($overwrite){
		$this -> overwrite = $overwrite;
	}

	public function getOverwrite(){
		return $this -> overwrite;
	}

	public function __tostring(){
		return "Name: ".$this -> name."\n\t\tChilds: ".implode(array_keys($this -> childs),', ').";\n\n";
	}

}

?>