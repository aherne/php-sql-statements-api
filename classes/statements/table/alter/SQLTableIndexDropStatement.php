<?php
require_once("AbstractSQLTableKeyDropStatement.php");
/**
 * Drop index from table.
*/
class SQLTableIndexDropStatement extends AbstractSQLTableKeyDropStatement {
	public function toString() {
		if(!$this->strConstraintName) throw new SQLLanguageException("strConstraintName is required!");
		return "DROP INDEX ".$this->strConstraintName." ON ".$this->strTableName;
	}
}