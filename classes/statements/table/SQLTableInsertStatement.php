<?php
require_once("AbstractSQLTableStatement.php");
require_once("../../clauses/SQLColumnsClause.php");
require_once("../../clauses/SQLRowClause.php");
/**
 * Encapsulates SQL statement: INSERT
 */
class SQLTableInsertStatement extends AbstractSQLTableStatement {
	protected $objColumns;
	protected $tblRows = array();
	
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
	 * Sets row to add.
	 * 
	 * @return	SQLRowClause
	 */
	public function setRow() {
		$objSQLRowClause = new SQLRowClause();
		$this->tblRows[] = $objSQLRowClause;
		return $objSQLRowClause;
	}
	
	public function toString() {
		if(sizeof($this->tblRows)==0) throw new SQLLanguageException("setRow is required!");
		
		$strOutput= "INSERT INTO ".$this->strTableName																			."\r\n".
				"(".implode(',',($this->objColumns?$this->objColumns->toString():$this->tblRows[0]->getColumns())).")  VALUES "	."\r\n";
		foreach($this->tblRows as $objSQLRowClause) {
			$strOutput.="(".$objSQLRowClause->toString()."),";
		}
		return substr($strOutput,0,-1);
	}
}