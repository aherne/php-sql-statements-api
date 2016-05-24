<?php
require_once("../../../../classes/statements/table/SQLTableCreateStatement.php");
require_once("../../constants/MySQLEngine.php");
require_once("../../clauses/MySQLColumnDefinitionClause.php");
/**
 * Encapsulates MySQL statement: CREATE TABLE
 */
class MySQLTableCreateStatement extends SQLTableCreateStatement {
	protected $strEngine;
	protected $blnIfNotExists=false;
	protected $blnIsTemporary=false;
	
	/**
	 * Set table engine.
	 * 
	 * @param MySQLEngine $objMySQLEngine
	 * @return MySQLTableCreateStatement
	 */
	public function setEngine($objMySQLEngine=MySQLEngine::MyISAM) {
		$this->strEngine=$objMySQLEngine;
		return $this;
	}
	
	/**
	 * Creates table only if it doesn't exist already.
	 * 
	 * @return MySQLTableCreateStatement
	 */
	public function setIfNotExists() {
		$this->blnIfNotExists = true;
		return $this;		
	}

	/**
	 * Creates table of temporary type.
	 *
	 * @return MySQLTableCreateStatement
	 */
	public function setTemporary() {
		$this->blnIsTemporary = true;
		return $this;		
	}

	/**
	 * Adds a column definition for table about to be created.
	 * 
	 * @return MySQLColumnDefinitionClause
	 */
	public function addColumn() {
		$objSQLColumnDefinitionClause = new MySQLColumnDefinitionClause();
		$this->tblSQLColumnDefinitionClause[]=$objSQLColumnDefinitionClause;
		return $objSQLColumnDefinitionClause;
	}
	
	public function toString() {
		if(sizeof($this->tblSQLColumnDefinitionClause)==0) throw new SQLLanguageException("addColumn is required!");
		$strOutput = "CREATE ".($this->blnIsTemporary?"TEMPORARY":"")." TABLE ".($this->blnIfNotExists?"IF NOT EXISTS":"")." ".$this->strTableName." 
				(";
		foreach($this->tblSQLColumnDefinitionClause as $objSQLColumnDefinitionClause) {
			$strOutput.=$objSQLColumnDefinitionClause->toString().",";
		}
		return substr($strOutput,0,-1).")".($this->strEngine?" ENGINE=".$this->strEngine:"");
	}
}