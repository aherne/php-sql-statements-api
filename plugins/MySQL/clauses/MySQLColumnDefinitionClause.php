<?php
/**
 * Encapsulates MySQL column definition clause.
 */
class MySQLColumnDefinitionClause extends SQLColumnDefinitionClause {	
	/**
	 * Sets column as unsigned (int)
	 * 
	 * @return MySQLColumnDefinitionClause
	 */
	public function setUnsigned() {
		$this->tblContents["IS_UNSIGNED"]=true;
		return $this;
	}
	
	/**
	 * Translates clause into string
	 * 
	 * (non-PHPdoc)
	 * @see AbstractSQLClause::toString()
	 */
	public function toString() {
		$output = "";
		$output .= $this->tblContents["NAME"];
		$output .= " " .$this->tblContents["DATA_TYPE"];
		if(isset($this->tblContents["DATA_LENGTH"]))		$output .= "(".$this->tblContents["DATA_LENGTH"].")";
		if(isset($this->tblContents["IS_UNSIGNED"]))		$output .= " UNSIGNED";
		if(!isset($this->tblContents["IS_NULLABLE"]))		$output .= " NOT NULL";
		if(isset($this->tblContents["IS_AUTOINCREMENT"]))	$output .= " AUTO_INCREMENT";
		if(isset($this->tblContents["IS_PRIMARY"]))			$output .= " PRIMARY KEY";
		if(isset($this->tblContents["DEFAULT_VALUE"]))		$output .= " DEFAULT ".$this->tblContents["DEFAULT_VALUE"];
		return $output;
	}
}