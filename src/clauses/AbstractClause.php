<?php
namespace Lucinda\Query;

/**
 * Abstract class covering base clause functionality
 */
abstract class AbstractClause {
	protected $contents = array();

	/**
	 * Transforms clause into a string
	 *
	 * @return string
	 */
	abstract public function toString();
}