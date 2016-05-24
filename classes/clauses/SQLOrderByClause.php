<?php
require_once("AbstractSQLClause.php");
/**
 * Encapsulates SQL clause: ORDER BY
 */
class SQLOrderByClause extends AbstractSQLClause {
	protected $strASC = "ASC";
	protected $strDESC = "DESC";
	
	/**
	 * Makes results sorted by input column ascendently.
	 * 
	 * @param 	string 					$strColumnName
	 * @return	SQLOrderByClause
	 */
	public function addAsc($strColumnName) {
		$this->tblContents[$strColumnName] = $this->strASC;
		return $this;
	}
	
	/**
	 * Makes results sorted by input column descendently.
	 * 
	 * @param 	string 					$strColumnName
	 * @return 	SQLOrderByClause
	 */
	public function addDesc($strColumnName) {
		$this->tblContents[$strColumnName] = $this->strDESC;
		return $this;
		
	}

	/**
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		$output = "";
		foreach($this->tblContents as $strKey=>$strValue) {
			$output .= $strKey.($strValue?" ".$strValue:"").", ";
		}
		return substr($output,0,-2);
	}
}