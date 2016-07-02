<?php

namespace CoreWine\ORM\Field\Schema;

class ModelField extends Field{
	

	public $relation;

	public function relation($relation){
		return $this;
	}

	public function getRelation(){

	}
}

?>