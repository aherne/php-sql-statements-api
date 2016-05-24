<?php
require_once("AbstractSQLSchemaStatement.php");
/**
 * Encapsulates SQL statement: CREATE SCHEMA
 */
class SQLSchemaCreateStatement extends AbstractSQLSchemaStatement{	
	public function toString() {
		return "CREATE SCHEMA ".$this->strSchemaName;
	}
}