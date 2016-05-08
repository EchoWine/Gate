<?php

namespace Basic\Schema;

use Item\Schema;

use Admin\Field\Schema as FieldSchema;

class User extends Schema{
	
	public $__entity = 'Basic\Entity\User';

	public $table = 'user';

	public function fields(){

		$this -> field(FieldSchema\StringRequiredField::class,'username');
		
		$this -> field(FieldSchema\StringRequiredField::class,'password') -> length(128);

		$this -> field(FieldSchema\StringRequiredField::class,'email');

	}


}


?>