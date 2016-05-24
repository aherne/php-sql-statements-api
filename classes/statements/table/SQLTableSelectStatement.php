<?php
require_once("AbstractSQLTableStatement.php");
require_once("../../clauses/SQLAliasClause.php");
require_once("../../clauses/SQLColumnsClause.php");
require_once("../../clauses/SQLJoinClause.php");
require_once("../../clauses/SQLWhereClause.php");
require_once("../../clauses/SQLWhereGroupClause.php");
require_once("../../clauses/SQLOrderByClause.php");
require_once("../../clauses/SQLLimitClause.php");
/**
 * Encapsulates SQL statement: SELECT
 * NOTE: Applies to SELECTs that do not use set operators (UNION, INTERSECT, EXCEPT).
 */
class SQLTableSelectStatement extends AbstractSQLTableStatement {
	protected $objTableAlias;
	protected $blnIsDistinct=false;
	protected $objSQLColumnsClause;
	protected $tblSQLJoinsClause=array();
	protected $objSQLWhereClause;
	protected $objSQLGroupByClause;
	protected $objSQLHavingClause;
	protected $objSQLOrderByClause;
	protected $objSQLLimitClause;
	
	/**
	 * Sets alias for current table.
	 * 
	 * @param 	string 											$strAlias
	 * @return 	SQLTableSelectStatement
	 */
	public function setTableAlias($strAlias) {
		$this->objTableAlias = new SQLAliasClause($this->strTableName, $strAlias);
		return $this;
	}
	
	/**
	 * Activates DISTINCT clause for select.
	 * 
	 * @return	SQLTableSelectStatement
	 */
	public function setDistinct() {
		$this->blnIsDistinct=true;
		return $this;
	}
	
	/**
	 * Sets columns to select. If not set, all columns are selected.
	 * 
	 * @return	SQLColumnsClause
	 */
	public function setColumns() {
		$objSQLColumnsClause = new SQLColumnsClause();
		$this->objSQLColumnsClause = $objSQLColumnsClause;
		return $objSQLColumnsClause;
	}
	
	/**
	 * Adds a LEFT JOIN to select statement.
	 * 
	 * @param 	string										$strTableName
	 * @return 	SQLTableSelectStatement
	 */
	public function setJoinLeft($strTableName) {
		$objSQLJoinClause = new SQLLeftJoinClause($strTableName);
		$this->tblSQLJoinsClause[]=$objSQLJoinClause;
		return $objSQLJoinClause;
	}
	
	/**
	 * Adds a RIGHT JOIN to select statement.
	 * 
	 * @param 	string										$strTableName
	 * @return 	SQLTableSelectStatement
	 */
	public function setJoinRight($strTableName) {
		$objSQLJoinClause = new SQLRightJoinClause($strTableName);
		$this->tblSQLJoinsClause[]=$objSQLJoinClause;
		return $objSQLJoinClause;
	}
	
	/**
	 * Adds a INNER JOIN to select statement.
	 * 
	 * @param 	string										$strTableName
	 * @return 	SQLJoinClause
	 */
	public function setJoinInner($strTableName) {
		$objSQLJoinClause = new SQLInnerJoinClause($strTableName);
		$this->tblSQLJoinsClause[]=$objSQLJoinClause;
		return $objSQLJoinClause;
	}
	
	/**
	 * Activates WHERE clause consisting of simple conditions (using a single logical operator). 
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
	 * Activates WHERE clause consisting of imbricate conditions (using multiple logical operators). 
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
	
	/**
	 * Activates GROUP BY clause for select.
	 * 
	 * @return 	SQLColumnsClause
	 */
	public function setGroupBy() {
		$objSQLColumnsClause = new SQLColumnsClause();
		$this->objSQLGroupByClause = $objSQLColumnsClause;
		return $objSQLColumnsClause;
	}
	
	/**
	 * Activates HAVING clause consisting of simple conditions (using a single logical operator). 
	 * 
	 * @return 	SQLWhereClause
	 */
	public function setHaving($strLogicalOperator=SQLLogicalOperator::_AND_){
		$objSQLWhereClause = new SQLWhereClause($strLogicalOperator);
		$this->objSQLHavingClause=$objSQLWhereClause;
		return $objSQLWhereClause;
	}
	
	/**
	 * Activates HAVING clause  consisting of imbricate conditions (using multiple logical operators). 
	 * 
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereGroupClause
	 */
	public function setHavingGroup($strLogicalOperator=SQLLogicalOperator::_AND_){
		$objSQLWhereGroupClause = new SQLWhereGroupClause($strSQLLogicalOperator);
		$this->objSQLHavingClause=$objSQLWhereGroupClause;
		return $objSQLWhereGroupClause;
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
	
	public function toString() {
		$strOutput = 
				"SELECT ".($this->blnIsDistinct?"DISTINCT":"")																."\r\n".
				($this->objSQLColumnsClause?$this->objSQLColumnsClause->toString():"*")										."\r\n".
				"FROM ".($this->objTableAlias?$this->objTableAlias->toString():$this->strTableName)							."\r\n";
		if(sizeof($this->tblSQLJoinsClause)>0) 	{
			foreach($this->tblSQLJoinsClause as $objSQLJoinClause) {
				$strOutput .= $objSQLJoinClause->toString()																	."\r\n";
			}
		}
		$strOutput .=
				($this->objSQLWhereClause?"WHERE ".$this->objSQLWhereClause->toString():"")									."\r\n".
				($this->objSQLGroupByClause?"GROUP BY ".$this->objSQLGroupByClause->toString():"")							."\r\n".
				($this->objSQLHavingClause?"HAVING ".$this->objSQLHavingClause->toString():"")								."\r\n".
				($this->objSQLOrderByClause?"ORDER BY ".$this->objSQLOrderByClause->toString():"")							."\r\n".
				($this->objSQLLimitClause?"LIMIT ".$this->objSQLLimitClause->toString():"");
		return $strOutput;
	}
} 