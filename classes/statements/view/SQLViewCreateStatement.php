<?php
require_once("AbstractSQLViewStatement.php");
/**
 * Generates SQL statement: CREATE VIEW
 */
class SQLViewCreateStatement extends AbstractSQLViewStatement {
	protected $strSQLTableSelectStatement;
	
	/**
	 * Sets SELECT statement for CREATE VIEW
	 * 
	 * @param	string	$strSQLTableSelectStatement
	 */
	public function setSelect($strSQLTableSelectStatement) {
		$this->strSQLTableSelectStatement=$strSQLTableSelectStatement;
	}
	
	public function toString() {
		if(!$this->strSQLTableSelectStatement) throw new SQLLanguageException("You must setSelect before creating view!");
		return "CREATE VIEW ".$this->strViewName." AS ".$this->strSQLTableSelectStatement;
	}
}