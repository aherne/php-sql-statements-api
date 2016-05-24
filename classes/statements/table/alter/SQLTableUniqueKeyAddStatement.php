<?php
require_once("AbstractSQLTableKeyAddStatement.php");
/**
 * Adds unique key constraint to table.
*/
class SQLTableUniqueKeyAddStatement extends AbstractSQLTableKeyAddStatement {
	public function toString() {
		if(!$this->objSQLColumnsClause) throw new SQLLanguageException("setColumns is required!");
		return "ALTER TABLE ".$this->strTableName." ADD ".($this->strConstraintName?"CONSTRAINT ".$this->strConstraintName:"")." UNIQUE (".$this->objSQLColumnsClause->toString().")";
	}
}