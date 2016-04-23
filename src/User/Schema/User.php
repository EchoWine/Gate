<?php

namespace User\Schema;

use Item\Schema as Schema;

class User extends Schema\Item{
	
	public $table = 'user';

	public function fields(){

		$this -> field(Schema\FieldString::class,'username');
		$this -> field(Schema\FieldString::class,'password') -> length(128);
		$this -> field(Schema\FieldString::class,'email');

	}


}


?>