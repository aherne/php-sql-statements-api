<?php
require_once("AbstractSQLClause.php");
require_once("SQLSetClause.php");
/**
 * Encapsulates SQL row clause (used by INSERT statements).
 */
class SQLRowClause extends SQLSetClause {	
	/**
	 * Gets columns from row.
	 * @return 	string[]
	 */
	public function getColumns() {
		return array_keys($this->tblContents);
	}
	
	/**
	 * Transforms clause to string
	 * 
	 * @return	string
	 */
	public function toString() {
		$output = "";		
		foreach($this->tblContents as $mixValue) {
			$output .= $mixValue.", ";
		}
		return substr($output,0,-2);
	}
}