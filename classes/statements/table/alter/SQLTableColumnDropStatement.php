<?php
require_once("../AbstractSQLTableStatement.php");
/**
 * Drops column from table.
*/
class SQLTableColumnDropStatement extends AbstractSQLTableStatement {
	protected $strColumnName;

	/**
	 * Sets name of column to drop.
	 *
	 * @param string $strConstraintName
	 */
	public function setColumnName($strColumnName) {
		$this->strColumnName = $strColumnName;
		return $this;
	}
	
	public function toString() {
		if(!$this->strColumnName) throw new SQLLanguageException("setColumnName is required!");
		return "ALTER TABLE ".$this->strTableName." DROP COLUMN ".$this->strColumnName;
	}
}