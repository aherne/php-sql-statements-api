<?php
require_once("AbstractSQLClause.php");
/**
 * Encapsulates SQL clause: SET
 */
class SQLSetClause extends AbstractSQLClause {
	
	/**
	 * Sets a column value.
	 * 
	 * @param	string 				$strColumnName
	 * @param	mixed				$mixValue
	 * @return	SQLSetClause
	 */
	public function set($strColumnName, $mixValue) {
		$this->tblContents[$strColumnName]=$mixValue;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		$output = "";		
		foreach($this->tblContents as $strKey=>$mixValue) {
			$output .= $strKey." = ".$mixValue.", ";
		}
		return substr($output,0,-2);
	}
}