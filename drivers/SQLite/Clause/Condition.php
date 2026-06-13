<?php

namespace Lucinda\Query\Vendor\SQLite\Clause;

use Lucinda\Query\Clause\Condition as DefaultCondition;
use Lucinda\Query\Vendor\SQLite\Operator\Comparison as SQLiteComparisonOperator;

/**
 * Encapsulates SQLite WHERE/ON clauses on top of SQL standard conditions
 */
final class Condition extends DefaultCondition
{
    /**
     * Sets up a "GLOB/NOT GLOB" condition.
     *
     * @param string|\Stringable $columnDefinition Definition of column/field (name or expression)
     * @param string $pattern Value of GLOB pattern to match
     * @param boolean $isTrue Whether condition is GLOB or NOT GLOB
     * @return DefaultCondition
     */
    public function setGlob(string|\Stringable $columnDefinition, string $pattern, bool $isTrue=true): DefaultCondition
    {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=($isTrue ? SQLiteComparisonOperator::GLOB : SQLiteComparisonOperator::NOT_GLOB);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }
}
