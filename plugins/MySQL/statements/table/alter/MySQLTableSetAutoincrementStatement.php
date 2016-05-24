<?php
require_once("../../../../../classes/statements/table/AbstractSQLTableStatement.php");

/**
 * Modifies AUTO_INCREMENT value for table.
 */
class MySQLTableSetAutoincrementStatement extends AbstractSQLTableStatement {
	protected $intNewValue;
	
	/**
	 * Sets new value for AUTO_INCREMENT column.
	 * 
	 * @param integer $intNewValue
	 */
	public function setNewValue($intNewValue) {
		$this->intNewValue = $intNewValue;
	}
	
	public function toString() {
		return "ALTER TABLE ".$this->strTableName." AUTO_INCREMENT = ".$this->intNewValue;;
	}
}