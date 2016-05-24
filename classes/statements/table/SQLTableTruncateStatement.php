<?php
require_once("AbstractSQLTableStatement.php");
/**
 * Encapsulates SQL statement: TRUNCATE TABLE
 */
class SQLTableTruncateStatement extends AbstractSQLTableStatement {
	public function toString() {
		return "TRUNCATE TABLE ".$this->strTableName;
	}
}