<?php

namespace CoreWine\ORM\Field\Email;

use CoreWine\ORM\Field\String\Schema as StringSchema;

class Schema extends StringSchema{
	
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