<?php
require_once("../../../classes/clauses/SQLWhereClause.php");
require_once("../constants/MySQLComparisonOperator.php");
require_once("../constants/MySQLLogicalOperator.php");
/**
 * Encapsulates MySQL clause: WHERE
 * Applies to WHEREs that use a single logical operator (see SQLLogicalOperator).
 */
class MySQLWhereClause extends SQLWhereClause {
	/**
	 * Sets up clause directly from constructor. 
	 * 
	 * @param	SQLLogicalOperator							$strLogicalOperator
	 * @throws	SQLLanguageException
	 */
	public function __construct($strLogicalOperator=MySQLLogicalOperator::_AND_) {
		if(!defined('MySQLLogicalOperator::'.$strLogicalOperator)) throw new SQLLanguageException("Invalid logical operator: ".$strLogicalOperator);
		$this->strCurrentLogicalOperator = $strLogicalOperator;
	}
	
	/**
	 * Sets up a "REGEXP/NOT REGEXP" condition.
	 *
	 * @param 	string 										$strColumnName
	 * @param 	string 										$strPattern
	 * @param 	boolean 									$blnIsLike
	 * @return 	SQLWhereClause
	 */
	public function setRegexp($strColumnName, $strPattern, $blnIsTrue=true) {
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=($blnIsTrue?MySQLComparisonOperator::REGEXP:MySQLComparisonOperator::NOT_REGEXP);
		$tblClause["VALUE"]=$strPattern;
		$this->tblContents[]=$tblClause;
		return $this;
	}
}