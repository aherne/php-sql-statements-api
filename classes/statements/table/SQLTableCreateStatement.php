<?php
require_once("AbstractSQLTableStatement.php");
require_once("../../clauses/SQLColumnDefinitionClause.php");
/**
 * Encapsulates SQL statement: CREATE TABLE. To be extended on vendor-level (since table structure is dependant on vendor)
 */
abstract class SQLTableCreateStatement extends AbstractSQLTableStatement {
	protected $tblSQLColumnDefinitionClause;

	/**
	 * Adds a column definition for table about to be created.
	 * 
	 * @return SQLColumnDefinitionClause
	 */
	abstract public function addColumn();
	
	public function toString() {
		if(sizeof($this->tblSQLColumnDefinitionClause)==0) throw new SQLLanguageException("addColumn is required!");
		$strOutput = "CREATE TABLE ".$this->strTableName."
				(";
		foreach($this->tblSQLColumnDefinitionClause as $objSQLColumnDefinitionClause) {
			$strOutput.=$objSQLColumnDefinitionClause->toString().",";
		}
		return substr($strOutput,0,-1).")";
	}
}