<?php
require_once("../../../../classes/statements/table/SQLTableUpdateStatement.php");

/**
 * Encapsulates MySQL statement: UPDATE
 */
class MySQLTableUpdateStatement extends SQLTableUpdateStatement {
	protected $blnIgnore=false;

	/**
	 * Ignores constraints errors when statement is ran.
	 *
	 * @return MySQLTableUpdateStatement
	 */
	public function setIgnore() {
		$this->blnIgnore = true;
		return $this;
	}
	
	public function toString() {
		if(sizeof($this->objSQLSetClause)==0) throw new SQLLanguageException("setSet is required!");
		return	"UPDATE ".($this->blnIgnore?"IGNORE":"")." ".$this->strTableName									."\r\n".
				"SET ".$this->objSQLSetClause->toString()															."\r\n".
				($this->objSQLWhereClause?"WHERE ".$this->objSQLWhereClause->toString():"");
	}	
}