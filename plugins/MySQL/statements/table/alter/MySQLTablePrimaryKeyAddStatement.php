<?php
require_once("../../../../../classes/statements/table/alter/SQLTablePrimaryKeyAddStatement.php");

/**
 * Adds primary key constraint to table.
*/
class MySQLTablePrimaryKeyAddStatement extends SQLTablePrimaryKeyAddStatement {
	protected $blnIgnore=false;
	
	/**
	 * If duplicates are found, only first match is kept.
	 */
	public function setIgnore() {
		$this->blnIgnore = true;
	}
	
	public function toString() {
		if(!$this->objSQLColumnsClause) throw new SQLLanguageException("setColumns is required!");
		return "ALTER ".($this->blnIgnore?"IGNORE":"")." TABLE ".$this->strTableName." ADD ".($this->strConstraintName?"CONSTRAINT ".$this->strConstraintName:"")." PRIMARY KEY (".$this->objSQLColumnsClause->toString().")";
	}
}