<?php
require_once("../../../../classes/statements/table/SQLTableInsertSelectStatement.php");

/**
 * Encapsulates MySQL statement: REPLACE ... SELECT
 */
class MySQLTableReplaceSelectStatement extends SQLTableInsertSelectStatement {
	public function toString() {
		if(!$this->objSQLTableSelectStatement) throw new SQLLanguageException("setSelect/setSelectGroup is required!");
		if(!$this->objColumns) throw new SQLLanguageException("setColumns is required!");
		
		return  "REPLACE INTO ".$this->strTableName																			."\r\n".
				"(".$this->objColumns->toString().")"																		."\r\n".
				$this->objSQLTableSelectStatement->toString();
	}
}