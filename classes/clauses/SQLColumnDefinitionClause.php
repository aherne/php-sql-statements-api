<?php
require_once("AbstractSQLClause.php");

/**
 * Encapsulates SQL column definition clause.
 * Must be implemented by each vendor.
 */
abstract class SQLColumnDefinitionClause extends AbstractSQLClause {
	/**
	 * Defines column name.substring
	 * 
	 * @param 	string 					$strColumnName
	 * @return 	SQLColumnDefinitionClause
	 */
	public function setName($strColumnName) {
		$this->tblContents["NAME"]=$strColumnName;
		return $this;
	}
	
	/**
	 * Defines column type & length.
	 * 
	 * @param 	string 					$strColumnDataType
	 * @return 	SQLColumnDefinitionClause
	 */
	public function setDataType($strColumnDataType) {
		$this->tblContents["DATA_TYPE"]=$strColumnDataType;
		return $this;
	}

	/**
	 * Sets column length in digits and precision, if of numeric types or simply length in characters, if of string types.
	 *
	 * @param	integer					$intDigits
	 * @param	integer					$intDigitsAfterPoint
	 * @return	SQLColumnDefinitionClause
	 */
	public function setLength($intDigits, $intDigitsAfterPoint=0) {
		$this->tblContents["DATA_LENGTH"]=($intDigitsAfterPoint?$intDigits.",".$intDigitsAfterPoint:$intDigits);
		return $this;
	}
	
	/**
	 * Defines column default value.
	 * 
	 * @param	mixed 					$mixValue
	 * @return 	SQLColumnDefinitionClause
	 */
	public function setDefaultValue($mixValue) {
		$this->tblContents["DEFAULT_VALUE"]=$mixValue;
		return $this;
	}
	
	/**
	 * Sets column as nullable.
	 * 
	 * @return	SQLColumnDefinitionClause
	 */
	public function setNullable() {
		$this->tblContents["IS_NULLABLE"]=true;
		return $this;
	}
	
	/**
	 * Sets column as primary key.
	 * 
	 * @return	SQLColumnDefinitionClause
	 */
	public function setPrimary() {
		$this->tblContents["IS_PRIMARY"]=true;
		return $this;
	}
	
	/**
	 * Sets column as autoincremented.
	 * 
	 * @return	SQLColumnDefinitionClause
	 */
	public function setAutoincrement() {
		$this->tblContents["IS_AUTOINCREMENT"]=true;
		return $this;
	}
}