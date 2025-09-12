<?php

namespace Lucinda\Query;

use Lucinda\Query\Clause\Alias;

/**
 * Validates various parts of a query.
 */
class Validator
{

    /**
     * Validates table definition, returning table name or derived table expression.
     *
     * @param string|Select|SelectGroup $table
     * @param string $alias
     * @return string
     * @throws Exception
     */
    public function validateTable($table, string $alias=""): string
    {
        if (is_string($table)) {
            return $table;
        } elseif ($table instanceof Select || $table instanceof SelectGroup) {
            if (!$alias) {
                throw new Exception("Derived table must have an alias");
            }
            return "(\n".$table->toString()."\r)";
        } else {
            throw new Exception("Table name must be string or be a Select / SelectGroup object");
        }
    }

    /**
     * Validates argument to be used in select fields (column definition or expression)
     *
     * @param string|Stringable $columnDefinition
     * @param string $columnAlias
     * @return string|Alias
     * @throws Exception
     */
    public function validateSelectField($columnDefinition, string $columnAlias="")
    {
        if ($columnDefinition instanceof Stringable) {
            if (!$columnAlias) {
                throw new Exception("When using expressions as fields, alias is mandatory");
            }
            return new Alias("(".$columnDefinition->toString().")", $columnAlias);
        } else {
            return ($columnAlias?new Alias($columnDefinition, $columnAlias):$columnDefinition);
        }
    }

    /**
     * Validates argument to be used in order by clause (column definition or expression)
     *
     * @param $columnDefinition
     * @return string
     * @throws Exception
     */
    public function validateOrderByField($columnDefinition): string
    {
        if (is_string($columnDefinition)) {
            return $columnDefinition;
        } else if ($columnDefinition instanceof Stringable) {
            return "(". $columnDefinition->toString() .")";
        } else {
            throw new Exception("Order by field must be string or Stringable object");
        }
    }

    /**
     * Validates argument to be used in condition (column definition or value)
     *
     * @param $columnDefinition
     * @return string
     * @throws Exception
     */
    public function validateCondition($columnDefinition): string
    {
        if ($columnDefinition instanceof Stringable) {
            return "(".$columnDefinition->toString().")";
        } else if (is_array($columnDefinition)) {
            return "(".implode(", ", $columnDefinition).")";
        } else {
            return $columnDefinition;
        }
    }
}