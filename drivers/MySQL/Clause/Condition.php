<?php
namespace Lucinda\Query\Vendor\MySQL\Clause;

use Lucinda\Query\Clause\Condition as DefaultCondition;
use Lucinda\Query\Vendor\MySQL\Operator\Comparison as MySQLComparisonOperator;
use Lucinda\Query\Vendor\MySQL\Operator\FulltextSearchType;
use Lucinda\Query\Vendor\MySQL\Operator\MatchType;

/**
 * Encapsulates MySQL WHERE/ON clauses that use a single logical operator, on top of SQL standard
 */
class Condition extends DefaultCondition
{
    /**
     * Sets up a "REGEXP/NOT REGEXP" condition.
     *
     * @param string $columnName Name of column/field.
     * @param string $pattern Value of REGEX pattern to match
     * @param boolean $isTrue Whether or not condition is REGEXP or NOT REGEXP
     * @return Condition
     */
    public function setRegexp(string $columnName, string $pattern, bool $isTrue=true): DefaultCondition
    {
        $clause = [];
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
     * @param MatchType $type One of enum values corresponding to fulltext match options (default: NATURAL LANGUAGE)
     * @return DefaultCondition
     */
    public function setMatchAgainst(array $columnNames, string $pattern, string $type=MatchType::NATURAL_LANGUAGE): DefaultCondition
    {
        $clause = [];
        $clause["KEY"] = "MATCH(".implode(",", $columnNames).")";
        $clause["COMPARATOR"] = "AGAINST";
        $clause["VALUE"] = "(".$pattern." ".$type.")";
        $this->contents[]=$clause;
        return $this;
    }
}
