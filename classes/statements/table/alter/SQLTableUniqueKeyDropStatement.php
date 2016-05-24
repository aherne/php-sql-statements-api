<?php
require_once("AbstractSQLTableKeyDropStatement.php");
/**
 * Drops unique key constraint from table.
 */
class SQLTableUniqueKeyDropStatement extends AbstractSQLTableKeyDropStatement {
	public function toString() {
		if(!$this->strConstraintName) throw new SQLLanguageException("strConstraintName is required!");
		return "ALTER TABLE ".$this->strTableName." DROP CONSTRAINT ".$this->strConstraintName;
	}
}