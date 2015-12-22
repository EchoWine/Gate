<?php

class stdDataPost extends stdData{

	public function setValue($f,$v = null){
		$this -> value = $v = isset($_POST[$f]) ? $_POST[$f] : $v;
	}
}
?>