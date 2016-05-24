<?php
require_once("AbstractSQLTableStatement.php");
require_once("../../clauses/SQLSetClause.php");
require_once("../../clauses/SQLWhereClause.php");
require_once("../../clauses/SQLWhereGroupClause.php");
/**
 * Encapsulates SQL statement: UPDATE
 */
class SQLTableUpdateStatement extends AbstractSQLTableStatement {
	protected $objSQLSetClause;
	protected $objSQLWhereClause;
	
	/**
	 * Sets SET clause.
	 * 
	 * @return SQLSetClause
	 */
	public function setSet() {
		$objSQLSetClause = new SQLSetClause();
		$this->objSQLSetClause = $objSQLSetClause;
		return $objSQLSetClause;
	}

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
		if(sizeof($this->objSQLSetClause)==0) throw new SQLLanguageException("setSet is required!");
		return	"UPDATE ".$this->strTableName																		."\r\n".
				"SET ".$this->objSQLSetClause->toString()															."\r\n".
				($this->objSQLWhereClause?"WHERE ".$this->objSQLWhereClause->toString():"");
	}
}