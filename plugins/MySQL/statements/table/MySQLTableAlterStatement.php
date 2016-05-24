<?php
require_once("../../../../classes/statements/table/SQLTableAlterStatement.php");
require_once("alter/MySQLTableColumnAddStatement.php");
require_once("alter/MySQLTableColumnChangeStatement.php");
require_once("alter/MySQLTableColumnModifyStatement.php");
require_once("alter/MySQLTableForeignKeyDropStatement.php");
require_once("alter/MySQLTablePrimaryKeyAddStatement.php");
require_once("alter/MySQLTablePrimaryKeyDropStatement.php");
require_once("alter/MySQLTableUniqueKeyAddStatement.php");
require_once("alter/MySQLTableUniqueKeyDropStatement.php");
require_once("alter/MySQLTableSetAutoincrementStatement.php");
require_once("alter/MySQLTableSetEngineStatement.php");

/**
 * Encapsulates MySQL statement: ALTER TABLE
 */
class MySQLTableAlterStatement extends SQLTableAlterStatement {
	
	/**
	 * (non-PHPdoc)
	 * @see SQLTableAlterStatement::dropForeignKey()
	 */
	public function dropForeignKey($strConstraintName) {
		$objSQLTableStatement = new MySQLTableForeignKeyDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see SQLTableAlterStatement::dropPrimaryKey()
	 */
	public function dropPrimaryKey($strConstraintName) {
		$objSQLTableStatement = new MySQLTablePrimaryKeyDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see SQLTableAlterStatement::dropUniqueKey()
	 */
	public function dropUniqueKey($strConstraintName) {
		$objSQLTableStatement = new MySQLTableUniqueKeyDropStatement($this->strTableName);
		$objSQLTableStatement->setConstraintName($strConstraintName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}

	/**
	 * Adds primary key to table.
	 * 
	 * @return	MySQLTablePrimaryKeyAddStatement
	 */
	public function addPrimaryKey() {
		$objSQLTableStatement = new MySQLTableUniqueKeyAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}

	/**
	 * Adds unique key to table.
	 * 
	 * @return	MySQLTableUniqueKeyAddStatement
	 */
	public function addUniqueKey() {
		$objSQLTableStatement = new MySQLTableUniqueKeyAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	/**
	 * Modifies column from current table.
	 *
	 * @return MySQLTableColumnModifyStatement
	*/
	public function modifyColumn() {
		$objSQLTableStatement = new MySQLTableColumnModifyStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}

	/**
	 * Changes column from current table.
	 *
	 * @param 	string											$strOldColumnName
	 * @return 	MySQLTableColumnChangeStatement
	 */
	public function changeColumn($strOldColumnName) {
		$objSQLTableStatement = new MySQLTableColumnChangeStatement($this->strTableName);
		$objSQLTableStatement->setOldColumnName($strOldColumnName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see SQLTableAlterStatement::addColumn()
	 */
	public function addColumn() {
		$objSQLTableStatement = new MySQLTableColumnAddStatement($this->strTableName);
		$this->objSQLTableStatement = $objSQLTableStatement;
		return $objSQLTableStatement;
	}	
	
	/**
	 * Modifies auto-increment value for current table.
	 * 
	 * @param 	integer 										$intNewValue
	 */
	public function setAutoIncrement($intNewValue) {
		$objSQLTableStatement = new MySQLTableSetAutoincrementStatement($this->strTableName);
		$objSQLTableStatement->setNewValue($intNewValue);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
	
	/**
	 * Modifies current table engine.
	 * 
	 * @param 	string 											$strEngineName
	 */
	public function setEngine($strEngineName=MySQLEngine::MyISAM) {
		$objSQLTableStatement = new MySQLTableSetEngineStatement($this->strTableName);
		$objSQLTableStatement->setEngine($strEngineName);
		$this->objSQLTableStatement = $objSQLTableStatement;
	}
}