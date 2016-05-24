<?php
require_once("../AbstractSQLTableStatement.php");
require_once("../../../clauses/SQLWhereClause.php");
require_once("../../../clauses/SQLWhereGroupClause.php");
/**
 * Adds check constraint to table.
 */
class SQLTableCheckAddStatement extends AbstractSQLTableStatement {
	protected $strConstraintName;
	protected $objSQLWhereClause;

	/**
	 * Sets name constraint about to be created will be identified with.
	 *
	 * @param string $strConstraintName
	 */
	public function setConstraintName($strConstraintName) {
		$this->strConstraintName = $strConstraintName;
	}

	/**
	 * Sets a simple condition (using a single logical operator).
	 * EXAMPLE: a=1 AND b=2 AND c=3 AND D=4
	 *
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereClause
	 */
	public function setCondition($strLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereClause = new SQLWhereClause($strLogicalOperator);
		$this->objSQLWhereClause=$objSQLWhereClause;
		return $objSQLWhereClause;
	}
	
	/**
	 * Sets a complex condition (using multiple logical operators).
	 * EXAMPLE: (a=1 AND b=2) OR (c=3 AND d=4)
	 *
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereGroupClause
	 */
	public function setConditionGroup($strSQLLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereGroupClause = new SQLWhereGroupClause($strSQLLogicalOperator);
		$this->objSQLWhereClause=$objSQLWhereGroupClause;
		return $objSQLWhereGroupClause;
	}
	
	public function toString() {
		if(!$this->objSQLWhereClause) throw new SQLLanguageException("setCondition/setConditionGroup is required!");
		return "ALTER TABLE ".$this->strTableName." ADD ".($this->strConstraintName?"CONSTRAINT ".$this->strConstraintName:"")."	CHECK (".$this->objSQLWhereClause->toString().")";
	}
}