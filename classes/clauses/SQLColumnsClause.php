<?php
require_once("AbstractSQLClause.php");
/**
 * Encapsulates SQL columns clause.
 */
class SQLColumnsClause extends AbstractSQLClause {
	
	/**
	 * Adds column to list.
	 * 
	 * @param string $strColumnName
	 * @return SQLColumnsClause
	 */
	public function addColumn($strColumnName) {
		$this->tblContents[]=$strColumnName;
		return $this;
	}
	
	/**
	 * Adds alias to list.
	 * 
	 * @param string $strColumnName
	 * @param string $strColumnAlias
	 * @return SQLColumnsClause
	 */
	public function addColumnAlias($strColumnName, $strColumnAlias) {
		$this->tblContents[]=new SQLAliasClause($strColumnName, $strColumnAlias);
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		$strOutput = "";
		if(!sizeof($this->tblContents)) return $strOutput;
		
		foreach($this->tblContents as $mixValue) {
			$strOutput .= ($mixValue instanceof SQLAliasClause?$mixValue->toString():$mixValue).", ";
		}
		
		return substr($strOutput,0,-2);
	} 
}