<?php
require_once("../../../../../classes/statements/table/alter/SQLTableUniqueKeyDropStatement.php");

/**
 * Drops unique key constraint from table.
 */
class MySQLTableUniqueKeyDropStatement extends SQLTableUniqueKeyDropStatement {
	public function toString() {
		if(!$this->strConstraintName) throw new SQLLanguageException("strConstraintName is required!");
		return "ALTER TABLE ".$this->strTableName." DROP INDEX ".$this->strConstraintName;
	}
}