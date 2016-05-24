<?php
require_once("AbstractSQLTableStatement.php");
require_once("../../clauses/SQLWhereClause.php");
require_once("../../clauses/SQLWhereGroupClause.php");
/**
 * Encapsulates SQL statement: DELETE
 */
class SQLTableDeleteStatement extends AbstractSQLTableStatement {
	protected $objSQLWhereClause;
	
	/**
	 * Sets a WHERE clause consisting of simple conditions (using a single logical operator). 
	 * EXAMPLE: a=1 AND b=2 AND c=3 AND D=4
	 * 
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereClause
	 */
	public function setWhere($strLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereClause = new SQLWhereClause($strLogicalOperator);
		$this->objSQLWhereClause=$objSQLWhereClause;
		return $objSQLWhereClause;
	}
	
	/**
	 * Sets a WHERE clause consisting of imbricate conditions (using multiple logical operators). 
	 * EXAMPLE: (a=1 AND b=2) OR (c=3 AND d=4)
	 * 
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereGroupClause
	 */
	public function setWhereGroup($strSQLLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereGroupClause = new SQLWhereGroupClause($strSQLLogicalOperator);
		$this->objSQLWhereClause=$objSQLWhereGroupClause;
		return $objSQLWhereGroupClause;
	}
	
	public function toString() {
		if(!$this->objSQLWhereClause) throw new SQLLanguageException("setWhere/setWhereGroup is required! If you really want to empty table, use truncate instead!");
		return "DELETE FROM ".$this->strTableName."\r\n".
				"WHERE ".$this->objSQLWhereClause->toString();
	}
}