<?php
require_once("../../../../classes/statements/table/AbstractSQLTableStatement.php");
/**
 * Encapsulates MySQL statement: RENAME TABLE
 */
class MySQLTableRenameStatement extends AbstractSQLTableStatement {
	protected $strNewTableName;
	
	/**
	 * Sets new table name
	 * 
	 * @param 	string 	$strNewTableName
	 * @return 	MySQLTableRenameStatement
	 */
	public function setNewName($strNewTableName) {
		$this->strNewTableName=$strNewTableName;
		return $this;
	}
	
	public function toString() {
		return "RENAME TABLE ".$this->strTableName." TO ".$this->strNewTableName;
	}
}