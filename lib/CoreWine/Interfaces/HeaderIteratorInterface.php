<?php

namespace CoreWine\Interfaces;

/**
 *
 * Header Interface.
 *
 */
interface HeaderIteratorInterface extends \iterator {
	public function rewind();
	public function current();
	public function key();
	public function next();
	public function valid();


	public function isValueValid();
	public function set($key, $value);
}