<?php
/**
 * Abstract model for server-level SQL statement generator.
 */
abstract class AbstractSQLServerStatement {
	/**
	 * Generates statement as string.
	 * @return string
	 */
	abstract public function toString();
}