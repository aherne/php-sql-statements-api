<?php
require_once("../../../../../classes/statements/table/alter/SQLTableColumnAddStatement.php");
require_once("../../../clauses/MySQLColumnDefinitionClause.php");

/**
 * Adds column to table.
 */
class MySQLTableColumnAddStatement extends SQLTableColumnAddStatement {
	protected $blnIsFirst;
	protected $strAfterColumnName;

	/**
	 * Sets column definition.
	 *
	 * @return MySQLColumnDefinitionClause
	 */
	public function setColumnDefinition() {
		$objSQLColumnDefinition = new MySQLColumnDefinitionClause();
		$this->objSQLColumnDefinitionClause = $objSQLColumnDefinition;
		return $objSQLColumnDefinition;
	}
	
	/**
	 * Places column first in table columns index order.
	 */
	public function setFirst() {
		$this->blnIsFirst = true;
	}
	
	/**
	 * Places column after another column in table columns index order.
	 * 
	 * @param string $strColumnName
	 */
	public function setAfter($strColumnName) {
		$this->strAfterColumnName=$strColumnName;
	}
	
	public function toString() {
		if(!$this->strColumnName) throw new SQLLanguageException("setColumnName is required!");
		if(!$this->objSQLColumnDefinitionClause) throw new SQLLanguageException("setColumnDefinition is required!");
		return "ALTER TABLE ".$this->strTableName." ADD COLUMN ".$this->objSQLColumnDefinitionClause->toString().($this->blnIsFirst?" FIRST":($this->strAfterColumnName?" AFTER ".$this->strAfterColumnName:""));
	}
}