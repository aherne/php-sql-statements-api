<?php
require_once("AbstractSQLClause.php");
/**
 * Encapsulates SQL clause: LIMIT
 */
class SQLLimitClause extends AbstractSQLClause {
	protected $intLimit;
	protected $intOffset;
	
	/**
	 * Sets up clause directly from constructor. 
	 * 
	 * @param integer $intLimit
	 * @param integer $intOffset
	 */
	public function __construct($intLimit, $intOffset=0) {
		$this->intLimit = $intLimit;
		$this->intOffset = $intOffset;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		return ($this->intOffset?$this->intLimit." OFFSET ".$this->intOffset:$this->intLimit);
	}
}