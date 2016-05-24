<?php
require_once("AbstractSQLClause.php");
require_once("SQLAliasClause.php");
require_once("SQLWhereClause.php");
require_once("SQLWhereGroupClause.php");

/**
 * Encapsulates SQL clause: LEFT OUTER JOIN
 */
class SQLLeftJoinClause extends SQLJoinClause {
	protected $strJoinType = "LEFT OUTER JOIN";
	
}

/**
 * Encapsulates SQL clause: RIGHT OUTER JOIN
 */
class SQLRightJoinClause extends SQLJoinClause {
	protected $strJoinType = "RIGHT OUTER JOIN";
}

/**
 * Encapsulates SQL clause: INNER JOIN
 */
class SQLInnerJoinClause extends SQLJoinClause {
	protected $strJoinType = "INNER JOIN";
}

/**
 * Encapsulates abstract SQL clause: JOIN
 */
abstract class SQLJoinClause extends AbstractSQLClause {
	protected $strJoinType;
	protected $strTableName;
	protected $objTableAlias;
	protected $objSQLWhereClause;

	/**
	 * Creates a join clause object
	 *
	 * @param 	string			$mixTableName
	 */
	public function __construct($strTableName) {
		$this->strTableName=$strTableName;
	}
	
	/**
	 * Sets an alias for current table.
	 * 
	 * @param 	$strAlias
	 * @return 	SQLJoinClause
	 */
	public function setTableAlias($strAlias) {
		$this->objTableAlias = new SQLAliasClause($this->strTableName, $strAlias);
		return $this;
	}

	/**
	 * Activates ON clause consisting of simple conditions (using a single logical operator).
	 * EXAMPLE: a=1 AND b=2 AND c=3 AND D=4
	 *
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereClause
	 */
	public function setOn($strLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereClause = new SQLWhereClause($strLogicalOperator);
		$this->objSQLWhereClause=$objSQLWhereClause;
		return $objSQLWhereClause;
	}
	
	/**
	 * Activates ON clause consisting of imbricate conditions (using multiple logical operators).
	 * EXAMPLE: (a=1 AND b=2) OR (c=3 AND d=4)
	 *
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @return 	SQLWhereGroupClause
	 */
	public function setOnGroup($strSQLLogicalOperator=SQLLogicalOperator::_AND_) {
		$objSQLWhereGroupClause = new SQLWhereGroupClause($strSQLLogicalOperator);
		$this->objSQLWhereClause=$objSQLWhereGroupClause;
		return $objSQLWhereGroupClause;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		return $this->strJoinType." ".($this->objTableAlias?$this->objTableAlias->toString():$this->strTableName)." ON ".$this->objSQLWhereClause->toString();
	}
}