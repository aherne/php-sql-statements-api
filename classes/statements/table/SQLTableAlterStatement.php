<?php
require_once("AbstractSQLTableStatement.php");
require_once("alter/SQLTableCheckAddStatement.php");
require_once("alter/SQLTableCheckDropStatement.php");
require_once("alter/SQLTableColumnAddStatement.php");
require_once("alter/SQLTableColumnDropStatement.php");
require_once("alter/SQLTableForeignKeyAddStatement.php");
require_once("alter/SQLTableForeignKeyDropStatement.php");
require_once("alter/SQLTableIndexAddStatement.php");
require_once("alter/SQLTableIndexDropStatement.php");
require_once("alter/SQLTablePrimaryKeyAddStatement.php");
require_once("alter/SQLTablePrimaryKeyDropStatement.php");
require_once("alter/SQLTableUniqueKeyAddStatement.php");
require_once("alter/SQLTableUniqueKeyDropStatement.php");
/**
 * Encapsulates SQL statement: ALTER TABLE. To be extended on vendor-level (since table structure is dependant on vendor)
 */
abstract class SQLTableAlterStatement extends AbstractSQLTableStatement {
	protected $objSQLTableStatement;
	
	public function __construct($strTableName, $strSchemaName="") {
		$this->strTableName = ($strSchemaName?$strSchemaName.".":"").$strTableName;
	}
	
	/**
	 * Adds a check constraint to table.
	 * 
	 * @return 	SQLTableCheckAddStatement
	 */
	public function addCheck() {
		$objSQLTableStatement = new SQLTableCheckAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	/**
	 * Adds a column to current table. To be implemented on vendor-level.
	 *
	 * @return 	SQLTableColumnAddStatement
	 */
	abstract public function addColumn();
	
	/**
	 * Adds a foreign key to table
	 *
	 * @return 	SQLTableForeignKeyAddStatement
	*/
	public function addForeignKey() {
		$objSQLTableStatement = new SQLTableForeignKeyAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	/**
	 * Adds index to table.
	 *
	 * @return 	SQLTableIndexAddStatement
	*/
	public function addIndex() {
		$objSQLTableStatement = new SQLTableIndexAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	/**
	 * Adds primary key to table.
	 *
	 * @return 	SQLTablePrimaryKeyAddStatement
	*/
	public function addPrimaryKey() {
		$objSQLTableStatement = new SQLTablePrimaryKeyAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	/**
	 * Adds unique key to table.
	 *
	 * @return 	SQLTableUniqueKeyAddStatement
	*/
	public function addUniqueKey() {
		$objSQLTableStatement = new SQLTableUniqueKeyAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	
	/**
	 * Drops column from current table.
	 *
	 * @param 	string 											$strColumnName
	*/
	public function dropColumn($strColumnName) {
		$objSQLTableStatement = new SQLTableColumnDropStatement($this->strTableName);
		$objSQLTableStatement->setColumnName($strColumnName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * Drops check constraint from current table.
	 *
	 * @param 	string 											$strConstraintName
	*/
	public function dropCheck($strConstraintName) {
		$objSQLTableStatement = new SQLTableCheckDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * Drops foreign key from current table.
	 *
	 * @param 	string 											$strConstraintName
	*/
	public function dropForeignKey($strConstraintName) {
		$objSQLTableStatement = new SQLTableForeignKeyDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * Drops index from current table.
	 *
	 * @param 	string 											$strConstraintName
	*/
	public function dropIndex($strConstraintName) {
		$objSQLTableStatement = new SQLTableIndexDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * Drops primary key from current table.
	 *
	 * @param 	string 											$strConstraintName
	*/
	public function dropPrimaryKey($strConstraintName) {
		$objSQLTableStatement = new SQLTablePrimaryKeyDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * Drops unique key from current table.
	 *
	 * @param 	string 											$strConstraintName
	*/
	public function dropUniqueKey($strConstraintName) {
		$objSQLTableStatement = new SQLTableUniqueKeyDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	public function toString() {
		if(!$this->objSQLTableStatement) throw new SQLLanguageException("No alter clause was set!");
		return $this->objSQLTableStatement->toString();
	}
}