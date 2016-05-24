<?php
require_once("MySQLTableColumnAddStatement.php");
/**
 * Modifies column from table.
 */
class MySQLTableColumnModifyStatement extends MySQLTableColumnAddStatement {
	public function toString() {
		if(!$this->strColumnName) throw new SQLLanguageException("setColumnName is required!");
		if(!$this->objSQLColumnDefinitionClause) throw new SQLLanguageException("setColumnDefinition is required!");
		return "ALTER TABLE ".$this->strTableName." MODIFY COLUMN ".$this->objSQLColumnDefinitionClause->toString().($this->blnIsFirst?" FIRST":($this->strAfterColumnName?" AFTER ".$this->strAfterColumnName:""));
	}
}