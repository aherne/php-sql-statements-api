<?php
require_once("../../../../classes/statements/table/SQLTableInsertStatement.php");

/**
 * Encapsulates MySQL statement: REPLACE
 */
class MySQLTableReplaceStatement extends SQLTableInsertStatement {	
	public function toString() {
		if(sizeof($this->tblRows)==0) throw new SQLLanguageException("setRow is required!");
		
		$strOutput= "REPLACE INTO ".$this->strTableName																			."\r\n".
				"(".implode(',',($this->objColumns?$this->objColumns->toString():$this->tblRows[0]->getColumns())).")  VALUES "	."\r\n";
		foreach($this->tblRows as $objSQLRowClause) {
			$strOutput.="(".$objSQLRowClause->toString()."),";
		}
		return substr($strOutput,0,-1);
	}
}