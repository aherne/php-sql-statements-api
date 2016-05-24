<?php
require_once("../../../../classes/statements/server/AbstractSQLServerStatement.php");
/**
 * Encapsulates MySQL statement: EXPLAIN
 */
class MySQLServerExplainStatement extends AbstractSQLServerStatement {
	protected $strSQLTableSelectStatement;
	
	/**
	 * Sets select to explain.
	 * 
	 * @param	string	$strSQLTableSelectStatement
	 */
	public function setSelect($strSQLTableSelectStatement) {
		$this->strSQLTableSelectStatement=$strSQLTableSelectStatement;
	}
	
	public function toString() {
		if(!$this->strSQLTableSelectStatement) throw new SQLLanguageException("setSelect is required!");
		return "EXPLAIN ".$this->strSQLTableSelectStatement;
	}
}