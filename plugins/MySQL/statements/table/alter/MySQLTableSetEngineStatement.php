<?php
require_once("../../../../../classes/statements/table/AbstractSQLTableStatement.php");
require_once("../../../constants/MySQLEngine.php");

/**
 * Modifies table engine.
 */
class MySQLTableSetEngineStatement extends AbstractSQLTableStatement {
	protected $strEngineName;
	
	/**
	 * Sets new MySQLEngine for currenttable. 
	 * 
	 * @param string $strEngineName
	 */
	public function setEngine($strEngineName=MySQLEngine::MyISAM) {
		$this->strEngineName = $strEngineName;
	}
	
	public function toString() {
		return "ALTER TABLE ".$this->strTableName." ENGINE = ".$this->strEngineName;
	}
}