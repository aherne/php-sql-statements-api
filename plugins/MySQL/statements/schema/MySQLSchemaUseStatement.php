<?php
require_once("../../../../classes/statements/schema/AbstractSQLSchemaStatement.php");
/**
 * Encapsulates MySQL statement: USE
 */
class MySQLSchemaUseStatement extends AbstractSQLSchemaStatement {	
	public function toString() {
		return "USE ".$this->strSchemaName;
	}
}