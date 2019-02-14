<?php
namespace Lucinda\Query;

require_once(dirname(__DIR__, 3)."/src/clauses/Condition.php");
require_once("MySQLComparisonOperator.php");
require_once("MySQLLogicalOperator.php");

/**
 * Encapsulates MySQL WHERE/ON clauses that use a single logical operator, on top of SQL standard
 */
class MySQLCondition extends Condition {
	/**
	 * Sets up a "REGEXP/NOT REGEXP" condition.
	 *
	 * @param string $columnName Name of column/field.
	 * @param string $pattern Value of REGEX pattern to match
	 * @param boolean $isTrue Whether or not condition is REGEXP or NOT REGEXP
	 * @return Condition
	 */
	public function setRegexp($columnName, $pattern, $isTrue=true) {
		$clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?MySQLComparisonOperator::REGEXP:MySQLComparisonOperator::NOT_REGEXP);
        $clause["VALUE"]=$pattern;
		$this->contents[]=$clause;
		return $this;
	}
}