<?php

namespace Basic\Schema;

use Item\Schema;

use Admin\Field\Schema as FieldSchema;

class User extends Schema{

	public $table = 'user';

	public function fields(){

		$this -> field(FieldSchema\IdField::class,'id') -> label('#');

		$this -> field(FieldSchema\UsernameField::class,'username') -> label('Username');
		
		$this -> field(FieldSchema\PasswordField::class,'password') -> minLength(8) -> maxLength(128) -> label('Password');

		$this -> field(FieldSchema\EmailField::class,'email') -> label('Email');

	}


}


?>