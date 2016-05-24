<?php
require_once("AbstractSQLTableKeyDropStatement.php");
/**
 * Drops check constraint from table.
 */
class SQLTableCheckDropStatement extends AbstractSQLTableKeyDropStatement {
	public function toString() {
		if(!$this->strConstraintName) throw new SQLLanguageException("strConstraintName is required!");
		return "ALTER TABLE ".$this->strTableName." DROP CONSTRAINT ".$this->strConstraintName;
	}
}