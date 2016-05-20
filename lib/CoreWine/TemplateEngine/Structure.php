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
	public $content = '';

	public function __construct($name,$type){
		$this -> name = $name;
		$this -> type = $type;
	}

	public function addChild($structure){
		$this -> childs[$structure -> name] = $structure;
	}

	public function findChildByName($name){
		foreach($this -> childs as $child){
			if($child -> name == $name)return $child;
		}
		return null;
	}

	public function setParent($structure){
		$this -> parent = $structure;
	}

	public function getParent(){
		return $this -> parent;
	}

	public function getName(){
		return $this -> name;
	}

	public function setContent($content){
		$this -> content = $content;
	}


	public function concatContent($content){
		$this -> content .= $content;
	}

	public function removeStructure($index){
		unset($this -> elements[$index]);
	}


}

?>