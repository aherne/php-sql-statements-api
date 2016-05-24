<?php
/**
 * Abstract model for table-level SQL statement generator.
 */
abstract class AbstractSQLTableStatement {
	protected $strTableName;
	
	public function __construct($strTableName) {
		$this->strTableName = $strTableName;
	}
	
	/**
	 * Generates statement as string.
	 * @return string
	 */
	abstract public function toString();
}