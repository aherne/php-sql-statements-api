<?php
namespace Lucinda\Query;
/**
 * Abstract model for table-level SQL statement generator.
 */
abstract class AbstractStatement {
	/**
	 * Generates statement as string.
	 * @return string
	 */
	abstract public function toString();
}