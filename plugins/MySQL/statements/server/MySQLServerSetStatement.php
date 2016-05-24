<?php
require_once("../../../../classes/statements/server/AbstractSQLServerStatement.php");
/**
 * Encapsulates MySQL statement: SET
 */
class MySQLServerSetStatement extends AbstractSQLServerStatement {
	protected $strSystemVariableName;
	protected $mixSystemVariableValue;
	protected $blnIsGlobalScope;
	
	/**
	 * Sets system variable.
	 * 
	 * @param string $strSystemVariableName
	 * @param mixed $mixSystemVariableValue
	 * @param boolean $blnIsGlobalScope
	 * @return MySQLServerSetStatement
	 */
	public function setCondition($strSystemVariableName, $mixSystemVariableValue, $blnIsGlobalScope=false) {
		$this->strSystemVariableName = $strSystemVariableName;
		$this->mixSystemVariableValue = $mixSystemVariableValue;
		$this->blnIsGlobalScope = $blnIsGlobalScope;
		return $this;
	}
	
	public function toString() {
		if(!$this->strSystemVariableName || !$this->strSystemVariableValue) throw new SQLLanguageException("setCondition is required!");
		return "SET ".($this->blnIsGlobalScope?"GLOBAL":"SESSION")." ".$this->strSystemVariableName."=".$this->mixSystemVariableValue;
	}
}