<?php


namespace Auth\Field\Schema;

use CoreWine\ORM\Field\Schema\Field;

class PasswordField extends Field{
	
	/**
	 * Model
	 */
	public $__model = 'Auth\Field\Model\PasswordField';

	/**
	 * Edit if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in edit/update operation
	 */
	public $editIfEmpty = false;

	/**
	 * Regex of field
	 */
	public $regex = "/^(.){0,255}$/iU";

	public $name = 'password';
	public $label = 'password';
	public $column = 'password';


}
?>