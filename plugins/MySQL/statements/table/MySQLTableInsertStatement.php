<?php
require_once("../../../../classes/statements/table/SQLTableInsertStatement.php");
/**
 * Encapsulates MySQL statement: INSERT
 */
class MySQLTableInsertStatement extends SQLTableInsertStatement {
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
		if(sizeof($this->tblRows)==0) throw new SQLLanguageException("setRow is required!");
		
		$strOutput= "INSERT ".($this->blnIgnore?"IGNORE":"")." INTO ".$this->strTableName																			."\r\n".
				"(".implode(',',($this->objColumns?$this->objColumns->toString():$this->tblRows[0]->getColumns())).")  VALUES "	."\r\n";
		foreach($this->tblRows as $objSQLRowClause) {
			$strOutput.="(".$objSQLRowClause->toString()."),";
		}
		return substr($strOutput,0,-1).($this->objOnDuplicateKeyUpdateClause?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->objOnDuplicateKeyUpdateClause->toString():"");
	}
}