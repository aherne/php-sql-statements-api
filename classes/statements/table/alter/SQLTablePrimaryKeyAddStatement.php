<?php
require_once("AbstractSQLTableKeyAddStatement.php");
/**
 * Adds primary key constraint to table.
*/
class SQLTablePrimaryKeyAddStatement extends AbstractSQLTableKeyAddStatement {
	public function toString() {
		if(!$this->objSQLColumnsClause) throw new SQLLanguageException("setColumns is required!");
		return "ALTER TABLE ".$this->strTableName." ADD ".($this->strConstraintName?"CONSTRAINT ".$this->strConstraintName:"")." PRIMARY KEY (".$this->objSQLColumnsClause->toString().")";
	}
}