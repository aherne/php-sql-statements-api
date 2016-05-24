<?php
require_once("MySQLTableColumnAddStatement.php");

/**
 * Changes column from table.
 */
class MySQLTableColumnChangeStatement extends MySQLTableColumnAddStatement {
	protected $strOldColumnName;
	
	/**
	 * Sets old column name.
	 * 
	 * @param string $strColumnName
	 */
	public function setOldColumnName($strColumnName) {
		$this->strOldColumnName = $strColumnName;
	}
	
	public function toString() {
		if(!$this->strColumnName) throw new SQLLanguageException("setColumnName is required!");
		if(!$this->objSQLColumnDefinitionClause) throw new SQLLanguageException("setColumnDefinition is required!");
		return "ALTER TABLE ".$this->strTableName." CHANGE COLUMN ".$this->strOldColumnName." ".$this->objSQLColumnDefinitionClause->toString().($this->blnIsFirst?" FIRST":($this->strAfterColumnName?" AFTER ".$this->strAfterColumnName:""));
	}
}