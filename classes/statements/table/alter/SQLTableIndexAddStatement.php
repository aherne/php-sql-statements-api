<?php
require_once("AbstractSQLTableKeyAddStatement.php");
/**
 * Adds index to table.
*/
class SQLTableIndexAddStatement extends AbstractSQLTableKeyAddStatement {
	public function toString() {
		if(!$this->objSQLColumnsClause) throw new SQLLanguageException("setColumns is required!");
		return "CREATE INDEX ".($this->strConstraintName?$this->strConstraintName:"")." ON ".$this->strTableName." (".$this->objSQLColumnsClause->toString().")";
	}
}