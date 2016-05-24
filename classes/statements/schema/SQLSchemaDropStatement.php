<?php
require_once("AbstractSQLSchemaStatement.php");
/**
 * Encapsulates SQL statement: DROP SCHEMA
 */
class SQLSchemaDropStatement extends AbstractSQLSchemaStatement {
	public function toString() {
		return "DROP SCHEMA ".$this->strSchemaName;
	}
}