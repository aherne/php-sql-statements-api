<?php
require_once("../../../../../classes/statements/table/alter/SQLTablePrimaryKeyDropStatement.php");

/**
 * Drops primary key constraint from table.
 */
class MySQLTablePrimaryKeyDropStatement extends SQLTablePrimaryKeyDropStatement {
	public function toString() {
		return "ALTER TABLE ".$this->strTableName." DROP PRIMARY KEY";
	}
}