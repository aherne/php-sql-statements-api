<?php
/**
 * Abstract model for view-level statement generator.
 */
abstract class AbstractSQLViewStatement {
	protected $strViewName;
	
	public function __construct($strViewName, $strSchemaName="") {
		$this->strViewName = ($strSchemaName?$strSchemaName.".":"").$strViewName;
	}

	/**
	 * Generates statement as string.
	 * @return string
	 */
	abstract public function toString();
}