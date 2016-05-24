<?php
require_once("../../../../classes/statements/server/AbstractSQLServerStatement.php");
/**
 * Encapsulates MySQL statement: KILL
 */
class MySQLServerKillProcessStatement extends AbstractSQLServerStatement {
	protected $intProcessId;
	
	/**
	 * Set process id to kill on server.
	 * 
	 * @param integer $intProcessId
	 * @return MySQLServerKillProcessStatement
	 */
	public function setProcessId($intProcessId) {
		$this->intProcessId = $intProcessId;
		return $this;
	}
	
	public function toString() {
		if(!$this->intProcessId) throw new SQLLanguageException("setProcessId is required!");
		return "KILL ".$this->intProcessId;
	}
}