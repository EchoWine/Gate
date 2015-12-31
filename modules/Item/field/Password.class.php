<?php

class Password extends _String{
	
	
	public function iniLabel(){
		$this -> label = 'Password';
	}

	/**
	 * Add the field to the query 'add'
	 * @param $a (array) array used in the query
	 */
	public function add(&$a){
		if($this -> getAdd()){
			$a[$this -> getColumnName()] = AuthModel::getHashPass($this -> getFormValue());
		}
	}
}
?>