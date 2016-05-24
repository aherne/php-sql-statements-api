<?php
require_once("AbstractSQLTableKeyDropStatement.php");
/**
 * Drops primary key constraint from table.
 */
class SQLTablePrimaryKeyDropStatement extends AbstractSQLTableKeyDropStatement {
	public function toString() {
		if(!$this->strConstraintName) throw new SQLLanguageException("strConstraintName is required!");
		return "ALTER TABLE ".$this->strTableName." DROP CONSTRAINT ".$this->strConstraintName;
	}
}