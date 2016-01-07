<?php

class stdDataGet extends stdData{

	public function setValue($f,$v = null){
		
		if(is_closure($v))
			$v = $v($f);
		

		$this -> value = $v = isset($_GET[$f]) ? $_GET[$f] : $v;
	}
}
?>