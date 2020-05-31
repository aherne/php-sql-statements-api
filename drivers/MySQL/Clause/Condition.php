<?php
namespace Lucinda\Query\Vendor\MySQL\Clause;

use Lucinda\Query\Clause\Condition as DefaultCondition;
use Lucinda\Query\Vendor\MySQL\Operator\Comparison as MySQLComparisonOperator;

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
}