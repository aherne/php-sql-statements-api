<?php
require_once("../AbstractSQLTableStatement.php");
require_once("../../../clauses/SQLColumnDefinitionClause.php");
/**
 * Adds column to table. To be extended on vendor-level (since table structure is dependant on vendor).
 *
 * @return 	SQLTableColumnAddStatement
 */
abstract class SQLTableColumnAddStatement extends AbstractSQLTableStatement {
	protected $objSQLColumnDefinitionClause;
	
	/**
	 * Sets column definition.
	 * 
	 * @return SQLColumnDefinitionClause
	 */
	abstract public function setColumnDefinition();
		
	public function toString() {
		if(!$this->objSQLColumnDefinitionClause) throw new SQLLanguageException("setColumnDefinition is required!");
		return "ALTER TABLE ".$this->strTableName." ADD COLUMN ".$this->objSQLColumnDefinitionClause->toString();
	}
}