<?php


namespace Auth\Field\Schema;

use CoreWine\ORM\Field\Schema\Field;

class PasswordField extends Field{
	
	/**
	 * Model
	 *
	 * @var string
	 */
	public $__model = 'Auth\Field\Model\PasswordField';

	/**
	 * Name
	 *
	 * @var string
	 */
	public $name = 'password';

	/**
	 * Column
	 *
	 * @var string
	 */
	public $column = 'password';

	/**
	 * Label
	 *
	 * @var string
	 */
	public $label = 'password';

	/**
	 * Regex of field
	 *
	 * @var string
	 */
	public $regex = "/^(.){0,255}$/iU";

	/**
	 * Required
	 *
	 * @var bool
	 */
	public $required = true;

	/**
	 * Max length
	 *
	 * @var int
	 */
	public $max_length = 128;

	/**
	 * Min length
	 *
	 * @var int
	 */
	public $min_length = 1;

	/**
	 * Edit if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in edit/update operation
	 *
	 * @var bool
	 */
	public $editIfEmpty = false;

	/**
	 * Include field in toArray operations
	 *
	 * @var bool
	 */
	public $enable_to_array = false;
}
?>