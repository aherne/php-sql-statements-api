<?php
require_once("AbstractSQLViewStatement.php");
/**
 * Generates SQL statement: DROP VIEW
 */
class SQLViewDropStatement extends AbstractSQLViewStatement {
	public function toString() {
		return "DROP VIEW ".$this->strViewName;
	}
}