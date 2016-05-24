<?php
require_once("../../../../classes/statements/table/SQLTableInsertSelectStatement.php");
/**
 * Encapsulates MySQL statement: INSERT ... SELECT
 */
class MySQLTableInsertSelectStatement extends SQLTableInsertSelectStatement {
	protected $blnIgnore=false;
	protected $objOnDuplicateKeyUpdateClause;

	/**
	 * Ignores constraints errors when statement is ran.
	 *
	 * @return MySQLTableInsertStatement
	 */
	public function setIgnore() {
		$this->blnIgnore = true;
		return $this;
	}
	
	/**
	 * Activates clause: ON DUPLICATE KEY UPDATE
	 * 
	 * @return SQLSetClause
	 */
	public function setOnDuplicateKeyUpdate() {
		$objSetClause = new SQLSetClause();
		$this->objOnDuplicateKeyUpdateClause=$objSetClause;
		return $objSetClause;
	}
	
	public function toString() {
		if(!$this->objSQLTableSelectStatement) throw new SQLLanguageException("setSelect/setSelectGroup is required!");
		if(!$this->objColumns) throw new SQLLanguageException("setColumns is required!");
		
		return  "INSERT INTO ".$this->strTableName																			."\r\n".
				"(".$this->objColumns->toString().")"																		."\r\n".
				$this->objSQLTableSelectStatement->toString().
				($this->objOnDuplicateKeyUpdateClause?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->objOnDuplicateKeyUpdateClause->toString():"");
	}
}