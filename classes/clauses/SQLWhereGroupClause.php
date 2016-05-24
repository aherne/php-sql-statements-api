<?php
require_once("AbstractSQLClause.php");
require_once("SQLWhereClause.php");

/**
 * Encapsulates SQL clause: WHERE
 * Applies to WHEREs that use multiple logical operators (see SQLLogicalOperator).
 */
class SQLWhereGroupClause extends AbstractSQLClause {
	private $strSQLLogicalOperator;
	
	/**
	 * Creates a condition group, using custom logical operator.
	 * 
	 * @param 	SQLLogicalOperator 			$strSQLLogicalOperator
	 */
	public function __construct($strSQLLogicalOperator=SQLLogicalOperator::_AND_) {
		$this->strSQLLogicalOperator = $strSQLLogicalOperator;
	}
	
	/**
	 * Adds single simple condition using a single logical operator to group.
	 * 
	 * @return 	SQLWhereClause
	 */
	public function addCondition($strLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereClause = new SQLWhereClause($strLogicalOperator);
		$this->tblContents[] = $objSQLWhereClause;
		return $objSQLWhereClause;
	}
	
	/**
	 * Creates a condition group, using custom logical operator.
	 * 
	 * @param	SQLLogicalOperator 			$strSQLLogicalOperator
	 * @return	SQLWhereGroupClause
	 */
	public function addGroup($strLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereGroupClause = new SQLWhereGroupClause($strLogicalOperator);
		$this->tblContents[]=$objSQLWhereGroupClause;
		return $objSQLWhereGroupClause;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		if($this->strSQLLogicalOperator == "NOT") {
			$strOutput="";
			foreach($this->tblContents as $objValue) {
				$strOutput.="(".(is_string($objValue)?$objValue:$objValue->toString()).") AND ";
			}
			return "NOT (".substr($strOutput,0,-4).")";
		} else {
			$strOutput="";
			foreach($this->tblContents as $objValue) {
				$strOutput.="(".$objValue->toString().") ".$this->strSQLLogicalOperator." ";
			}
			return "(".substr($strOutput, 0, -strlen($this->strSQLLogicalOperator)-1).")";
		}
	}
}