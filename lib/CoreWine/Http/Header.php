<?php

namespace CoreWine\Response\Http;

use CoreWine\Utils\Collection as Collection;

/*
 * Represents a Header collection
 *
 */
class Header extends Collection {

	/**
	 * Constructor
	 *
	 * @param array $headers 		The headers
	 * @return null				
	 */
	public function __construct($headers = []) {
		if (!is_array($headers)) {
			throw new \InvalidArgumentException("Invalid headers provided.");
		}

		parent::__construct($headers);

	}
}