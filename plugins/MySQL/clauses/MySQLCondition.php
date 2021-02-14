<?php
namespace Lucinda\Query;

require_once(dirname(dirname(dirname(__DIR__)))."/src/clauses/Condition.php");
require_once("MySQLComparisonOperator.php");
require_once("MySQLLogicalOperator.php");
require_once("MySQLMatchType.php");

/**
 * Encapsulates MySQL WHERE/ON clauses that use a single logical operator, on top of SQL standard
 */
class MySQLCondition extends Condition
{
    /**
     * Sets up a "REGEXP/NOT REGEXP" condition.
     *
     * @param string $columnName Name of column/field.
     * @param string $pattern Value of REGEX pattern to match
     * @param boolean $isTrue Whether or not condition is REGEXP or NOT REGEXP
     * @return Condition
     */
    public function setRegexp($columnName, $pattern, $isTrue=true)
    {
        $clause = array();
        $clause["KEY"]=$columnName;
        $clause["COMPARATOR"]=($isTrue?MySQLComparisonOperator::REGEXP:MySQLComparisonOperator::NOT_REGEXP);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }  
    
    /**
     * Sets a fulltext MATCH/AGAINST condition
     *
     * @param string[] $columnNames List of column names to match
     * @param string $pattern Value of pattern to match
     * @param MySQLMatchType $type One of enum values corresponding to fulltext match options (default: NATURAL LANGUAGE)
     * @return Condition
     */
    public function setMatchAgainst($columnNames, $pattern, $type=MySQLMatchType::NATURAL_LANGUAGE)
    {
        $clause = [];
        $clause["KEY"] = "MATCH(".implode(",", $columnNames).")";
        $clause["COMPARATOR"] = "AGAINST";
        $clause["VALUE"] = "(".$pattern." ".$type.")";
        $this->contents[]=$clause;
        return $this;
    }
}
