<?php
require_once("../AbstractSQLTableStatement.php");
require_once("../../../clauses/SQLColumnsClause.php");
/**
 * Implements standard SQL constraint add logic.
 */
abstract class AbstractSQLTableKeyAddStatement extends AbstractSQLTableStatement {
	protected $strConstraintName;
	protected $objSQLColumnsClause;
	
	/**
	 * Sets name constraint about to be created will be identified with.
	 * 
	 * @param string $strConstraintName
	 */
	public function setConstraintName($strConstraintName) {
		$this->strConstraintName = $strConstraintName;
	}
	
	/**
	 * Sets columns constraint applies to.
	 * 
	 * @return SQLColumnsClause
	 */
	public function setColumns() {
		$objColumns = new SQLColumnsClause();
		$this->objSQLColumnsClause = $objColumns;
		return $objColumns;
	}
}