<?php
require_once("SQLTableSelectStatement.php");
require_once("../../constants/SQLSetOperator.php");
/**
 * Encapsulates SQL statement: SELECT
 * Applies to SELECTs that use set operators (UNION, INTERSECT, EXCEPT).
 */
class SQLTableSelectGroupStatement  {
	protected $strSQLSetOperator;
	protected $objSQLOrderByClause;
	protected $objSQLLimitClause;
	protected $tblContents=array();
	
	/**
	 * Creates a select using a custom set operator.
	 * 
	 * @param SQLSetOperator $objSQLSetOperator
	 */
	public function __construct($objSQLSetOperator=SQLSetOperator::UNION) {
		$this->strSQLSetOperator = $objSQLSetOperator;
	}

	/**
	 * Adds single simple condition using a single logical operator to group.
	 *
	 * @param 	string					 		$strTableName
	 * @return 	SQLTableSelectStatement
	 */
	public function addSelect($strTableName) {
		$objSQLTableSelectStatement = new SQLTableSelectStatement($strTableName);
		$this->tblContents[] = $objSQLTableSelectStatement;
		return $objSQLTableSelectStatement;
	}
	
	/**
	 * Activates ORDER BY clause for select.
	 * 
	 * @return 	SQLOrderByClause
	 */
	public function setOrderBy() {
		$objSQLOrderByClause = new SQLOrderByClause();
		$this->objSQLOrderByClause = $objSQLOrderByClause;
		return $objSQLOrderByClause;
	}
	
	/**
	 * Activates LIMIT clause for select.
	 * 
	 * @param 	integer 										$intLimit
	 * @param 	integer 										$intOffset
	 * @return 	SQLTableSelectStatement
	 */
	public function setLimit($intLimit, $intOffset=0) {
		$this->objSQLLimitClause = new SQLLimitClause($intLimit, $intOffset);
		return $this;
	}

	/**
	 * Generates statement as string.
	 * @return string
	 */
	public function toString() {
			$strOutput="";
			foreach($this->tblContents as $objValue) {
				$strOutput.="(".$objValue->toString().")"."\r\n".$this->strSQLSetOperator."\r\n";
			}
			$strOutput = substr($strOutput, 0, -strlen($this->strSQLSetOperator)-2);
			return $strOutput.
					($this->objSQLOrderByClause?"ORDER BY ".$this->objSQLOrderByClause->toString():"")."\r\n".
					($this->objSQLLimitClause?"LIMIT ".$this->objSQLLimitClause->toString():"");
	}
} 