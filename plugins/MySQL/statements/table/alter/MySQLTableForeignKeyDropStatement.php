<?php
require_once("../../../../../classes/statements/table/alter/SQLTableForeignKeyDropStatement.php");

/**
 * Changes column from table.
 */
class MySQLTableForeignKeyDropStatement extends SQLTableForeignKeyDropStatement {
	public function toString() {
		if(!$this->strConstraintName) throw new SQLLanguageException("strConstraintName is required!");
		return "ALTER TABLE ".$this->strTableName." DROP FOREIGN KEY ".$this->strConstraintName;
	}
}