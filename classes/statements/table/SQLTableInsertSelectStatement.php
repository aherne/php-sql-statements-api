<?php
require_once("AbstractSQLTableStatement.php");
require_once("../../clauses/SQLColumnsClause.php");
require_once("SQLTableSelectStatement.php");
require_once("SQLTableSelectGroupStatement.php");
/**
 * Encapsulates SQL statement: INSERT ... SELECT
 */
class SQLTableInsertSelectStatement extends AbstractSQLTableStatement {
	protected $objColumns;
	protected $objSQLTableSelectStatement;
	
	/**
	 * Sets columns to insert into. 
	 * NOTE: If not supplied, it's automatically generated from SQLRowClause.
	 * 
	 * @return	SQLColumnsClause
	 */
	public function setColumns() {
		$objColumns = new SQLColumnsClause();
		$this->objColumns = $objColumns;
		return $objColumns;
	}
	
	/**
	 * Sets rows to add implicitly, via a SELECT statement.
	 * 
	 * @param	string			$strTableName
	 * @return	SQLTableSelectStatement
	 */
	public function setSelect($strTableName) {
		$objSQLTableSelectStatement = new SQLTableSelectStatement($strTableName);
		$this->objSQLTableSelectStatement=$objSQLTableSelectStatement;
		return $objSQLTableSelectStatement;
	}
	
	/**
	 * Sets rows to add implicitly, via a SELECT statement using SET SQL operators.
	 * 
	 * @param	SQLSetOperator	$objSQLSetOperator
	 * @return	SQLTableSelectGroupStatement
	 */
	public function setSelectGroup($objSQLSetOperator=SQLSetOperator::UNION) {
		$objSQLTableSelectStatement = new SQLTableSelectGroupStatement($objSQLSetOperator);
		$this->objSQLTableSelectStatement=$objSQLTableSelectStatement;
		return $objSQLTableSelectStatement;
	}
	
	public function toString() {
		if(!$this->objSQLTableSelectStatement) throw new SQLLanguageException("setSelect/setSelectGroup is required!");
		if(!$this->objColumns) throw new SQLLanguageException("setColumns is required!");
		
		return  "INSERT INTO ".$this->strTableName																			."\r\n".
				"(".$this->objColumns->toString().")"																		."\r\n".
				$this->objSQLTableSelectStatement->toString();
	}
}