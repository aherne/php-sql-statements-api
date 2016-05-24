<?php
require_once("AbstractSQLTableKeyAddStatement.php");
require_once("../../../constants/SQLForeignKeyAction.php");
/**
 * Adds foreign key constraint to table.
*/
class SQLTableForeignKeyAddStatement extends AbstractSQLTableKeyAddStatement {
	protected $strReferencedTableName;
	protected $objSQLReferencedColumnsClause;
	protected $strActionOnDelete;
	protected $strActionOnUpdate;
	
	/**
	 * Sets name of table foreign key references.
	 * 
	 * @param string $strTableName
	 */
	public function setReferencedTable($strTableName) {
		$this->strReferencedTableName = $strTableName;
	}
	
	/**
	 * Sets columns from referenced table foreign key applies to.
	 * 
	 * @return SQLColumnsClause
	 */
	public function setReferencedColumns() {
		$objColumns = new SQLColumnsClause();
		$this->objSQLReferencedColumnsClause = $objColumns;
		return $objColumns;
	}
	
	/**
	 * Sets SQLForeignKeyAction to perform when DELETE is performed on referenced columns. 
	 * 
	 * @param string $strSQLForeignKeyAction
	 */
	public function setActionOnDelete($strSQLForeignKeyAction=SQLForeignKeyAction::CASCADE) {
		$this->strActionOnDelete = $strSQLForeignKeyAction;
	}
	
	/**
	 * Sets SQLForeignKeyAction to perform when UPDATE is performed on referenced columns.
	 * 
	 * @param string $strSQLForeignKeyAction
	 */
	public function setActionOnUpdate($strSQLForeignKeyAction=SQLForeignKeyAction::CASCADE) {
		$this->strActionOnUpdate = $strSQLForeignKeyAction;
	}

	public function toString() {
		if(!$this->objSQLColumnsClause) throw new SQLLanguageException("setColumns is required!");
		if(!$this->strReferencedTableName) throw new SQLLanguageException("setReferencedTable is required!");
		if(!$this->objSQLReferencedColumnsClause) throw new SQLLanguageException("setReferencedColumns is required!");
		
		return "ALTER TABLE ".$this->strTableName." ADD ".($this->strConstraintName?"CONSTRAINT ".$this->strConstraintName:"")." FOREIGN KEY (".$this->objSQLColumnsClause->toString().") REFERENCES ".$this->strReferencedTableName."(".$this->objSQLReferencedColumnsClause->toString().") ".($this->strActionOnDelete?"ON DELETE ".$this->strActionOnDelete:"")." ".($this->strActionOnUpdate?"ON UPDATE ".$this->strActionOnUpdate:"");
	}
}