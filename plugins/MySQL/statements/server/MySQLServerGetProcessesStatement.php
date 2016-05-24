<?php
require_once("../../../../classes/statements/server/AbstractSQLServerStatement.php");
/**
 * Encapsulates MySQL statement: SHOW FULL PROCESSLIST
 */
class MySQLServerGetProcessesStatement extends AbstractSQLServerStatement {
	protected $blnFullList = false;
	
	/**
	 * Sets list to include processes not by current user.
	 * 
	 * @return MySQLServerGetProcessesStatement
	 */
	public function setFull() {
		$this->blnFullList = true;
		return $this;
	}
	
	public function toString() {
		return "SHOW ".($this->blnFullList?"FULL":"")." PROCESSLIST";
	}
}