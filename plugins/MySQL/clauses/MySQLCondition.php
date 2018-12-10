<?php
namespace Lucinda\Query;

require_once(dirname(dirname(dirname(__DIR__)))."/src/clauses/Condition.php");
require_once("MySQLComparisonOperator.php");

/**
 * Encapsulates MySQL clause: WHERE
 */
class MySQLCondition extends Condition {
	/**
	 * Sets up a "REGEXP/NOT REGEXP" condition.
	 *
	 * @param 	string 										$strColumnName
	 * @param 	string 										$strPattern
	 * @param 	boolean 									$blnIsLike
	 * @return 	Condition
	 */
	public function setRegexp($strColumnName, $strPattern, $blnIsTrue=true) {
		$tblClause = array();
		$tblClause["KEY"]=$strColumnName;
		$tblClause["COMPARATOR"]=($blnIsTrue?MySQLComparisonOperator::REGEXP:MySQLComparisonOperator::NOT_REGEXP);
		$tblClause["VALUE"]=$strPattern;
		$this->contents[]=$tblClause;
		return $this;
	}
}