<?php

namespace CoreWine\ORM\Field\Schema;

class EmailField extends Field{
	
	/**
	 * Model
	 *
	 * @var string
	 */
	public $__model = 'CoreWine\ORM\Field\Model\EmailField';

	/**
	 * Name
	 *
	 * @var string
	 */
	public $name = 'email';

	/**
	 * Column
	 *
	 * @var string
	 */
	public $column = 'email';

	/**
	 * Label
	 *
	 * @var string
	 */
	public $label = 'email';

	/**
	 * Regex of field
	 *
	 * @var string
	 */
	public $regex = "/^.+\@.+\..+$/iU";

	/**
	 * Unique
	 *
	 * @var bool
	 */
	public $unique = true;



}
?>