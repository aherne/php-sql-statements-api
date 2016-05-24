<?php
require_once("AbstractSQLTableStatement.php");
/**
 * Encapsulates SQL statement: DROP TABLE
 */
class SQLTableDropStatement extends AbstractSQLTableStatement {
	public function toString() {
		return "DROP TABLE ".$this->strTableName;
	}
}