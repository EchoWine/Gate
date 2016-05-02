<?php

namespace AdminAuth\Schema;

use Item\Schema;

use Item\Field\Schema as FieldSchema;

class User extends Schema{
	
	public $__entity = 'AdminAuth\Entity\User';

	public $table = 'user';

	public function fields(){

		$this -> field(FieldSchema\StringField::class,'username');
		
		$this -> field(FieldSchema\StringField::class,'password') -> length(128);

		$this -> field(FieldSchema\StringField::class,'email');

	}


}


?>