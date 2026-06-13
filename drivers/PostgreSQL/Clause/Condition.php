<?php

namespace Lucinda\Query\Vendor\PostgreSQL\Clause;

use Lucinda\Query\Clause\Condition as DefaultCondition;
use Lucinda\Query\Select;
use Lucinda\Query\SelectGroup;
use Lucinda\Query\Vendor\PostgreSQL\Operator\Comparison as PostgreSQLComparisonOperator;

/**
 * Encapsulates PostgreSQL WHERE/ON clauses on top of SQL standard conditions
 */
final class Condition extends DefaultCondition
{
    /**
     * Sets up an "ILIKE/NOT ILIKE" condition.
     *
     * @param string|\Stringable $columnDefinition Definition of column/field (name or expression)
     * @param string $pattern Value of pattern to match
     * @param boolean $isTrue Whether condition is ILIKE or NOT ILIKE
     * @return DefaultCondition
     */
    public function setILike(string|\Stringable $columnDefinition, string $pattern, bool $isTrue=true): DefaultCondition
    {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        $clause["COMPARATOR"]=($isTrue ? PostgreSQLComparisonOperator::ILIKE : PostgreSQLComparisonOperator::NOT_ILIKE);
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets up a PostgreSQL regular expression condition.
     *
     * @param string|\Stringable $columnDefinition Definition of column/field (name or expression)
     * @param string $pattern Value of regex pattern to match
     * @param boolean $isTrue Whether condition is positive or negative
     * @param boolean $caseSensitive Whether match is case-sensitive
     * @return DefaultCondition
     */
    public function setRegexp(
        string|\Stringable $columnDefinition,
        string $pattern,
        bool $isTrue=true,
        bool $caseSensitive=true
    ): DefaultCondition {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($columnDefinition);
        if ($caseSensitive) {
            $clause["COMPARATOR"]=($isTrue ? PostgreSQLComparisonOperator::REGEXP : PostgreSQLComparisonOperator::NOT_REGEXP);
        } else {
            $clause["COMPARATOR"]=(
                $isTrue ? PostgreSQLComparisonOperator::REGEXP_CASE_INSENSITIVE : PostgreSQLComparisonOperator::NOT_REGEXP_CASE_INSENSITIVE
            );
        }
        $clause["VALUE"]=$pattern;
        $this->contents[]=$clause;
        return $this;
    }

    /**
     * Sets up a PostgreSQL full-text search condition using @@.
     *
     * @param string|\Stringable $vectorExpression TS vector expression (eg: to_tsvector('english', body))
     * @param string|Select|SelectGroup $queryExpression TS query expression (eg: plainto_tsquery('english', :q))
     * @return DefaultCondition
     */
    public function setFullTextSearch(
        string|\Stringable $vectorExpression,
        string|Select|SelectGroup $queryExpression
    ): DefaultCondition {
        $clause = [];
        $clause["KEY"]=$this->validator->validateCondition($vectorExpression);
        $clause["COMPARATOR"]=PostgreSQLComparisonOperator::FULL_TEXT_MATCH;
        $clause["VALUE"]=$this->validator->validateCondition($queryExpression);
        $this->contents[]=$clause;
        return $this;
    }
}
