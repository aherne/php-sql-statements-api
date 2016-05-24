<?php
require_once("../../../../../classes/statements/table/alter/SQLTableUniqueKeyAddStatement.php");

/**
 * Adds unique key constraint to table.
*/
class MySQLTableUniqueKeyAddStatement extends SQLTableUniqueKeyAddStatement {
	protected $blnIgnore=false;

	/**
	 * If duplicates are found, only first match is kept.
	 */
	public function setIgnore() {
		$this->blnIgnore = true;
	}

	public function toString() {
		if(!$this->objSQLColumnsClause) throw new SQLLanguageException("setColumns is required!");
		return "ALTER ".($this->blnIgnore?"IGNORE":"")." TABLE ".$this->strTableName." ADD ".($this->strConstraintName?"CONSTRAINT ".$this->strConstraintName:"")." UNIQUE (".$this->objSQLColumnsClause->toString().")";
	}
}