<?php
/**
 * Abstract model for schema-level SQL statement generator.
 */
abstract class AbstractSQLSchemaStatement {
	protected $strSchemaName;
	
	public function __construct($strSchemaName) {
		$this->strSchemaName = $strSchemaName;
	}
	
	/**
	 * Generates statement as string.
	 * @return string
	 */
	abstract public function toString();
}