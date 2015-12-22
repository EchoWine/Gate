<?php

class stdDataGet extends stdData{

	public function setValue($f,$v = null){
		$this -> value = $v = isset($_GET[$f]) ? $_GET[$f] : $v;
	}
}
?>