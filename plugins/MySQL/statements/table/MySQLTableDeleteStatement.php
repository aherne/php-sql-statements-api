<?php
require_once("../../../../classes/statements/table/SQLTableDeleteStatement.php");
/**
 * Encapsulates MySQL statement: DELETE
 */
class MySQLTableDeleteStatement extends SQLTableDeleteStatement {
	protected $blnIgnore=false;
	
	/**
	 * Ignores constraints errors when statement is ran.
	 * 
	 * @return MySQLTableDeleteStatement
	 */
	public function setIgnore() {
		$this->blnIgnore = true;
		return $this;
	}
	
	public function toString() {
		if(sizeof($this->objSQLWhereClause)==0) throw new SQLLanguageException("setWhere is required! If you really want to empty table, use truncate instead!");
		return "DELETE ".($this->blnIgnore?"IGNORE":"")." FROM ".$this->strTableName."\r\n".
				"WHERE ".$this->objSQLWhereClause->toString();
	}
}