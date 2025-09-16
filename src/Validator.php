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
    public function validateTable(string|Select|SelectGroup $table, string $alias=""): string
    {
        if (is_string($table)) {
            return $table;
        } else {
            if (!$alias) {
                throw new Exception("Derived table must have an alias");
            }
            return "(\n".$table."\r)";
        }
    }

    /**
     * Validates argument to be used in select fields (column definition or expression)
     *
     * @param string|\Stringable $columnDefinition
     * @param string $columnAlias
     * @return string|Alias
     * @throws Exception
     */
    public function validateSelectField(string|\Stringable $columnDefinition, string $columnAlias=""): string|Alias
    {
        if ($columnDefinition instanceof \Stringable) {
            if (!$columnAlias) {
                throw new Exception("When using expressions as fields, alias is mandatory");
            }
            return new Alias("(".$columnDefinition.")", $columnAlias);
        } else {
            return ($columnAlias?new Alias($columnDefinition, $columnAlias):$columnDefinition);
        }
    }

    /**
     * Validates argument to be used in order by clause (column definition or expression)
     *
     * @param string|\Stringable $columnDefinition
     * @return string
     */
    public function validateOrderByField(string|\Stringable $columnDefinition): string
    {
        if (is_string($columnDefinition)) {
            return $columnDefinition;
        } else {
            return "(". $columnDefinition .")";
        }
    }

    /**
     * Validates argument to be used in condition (column definition or value)
     *
     * @param int|string|float|array|\Stringable $columnDefinition
     * @return string
     */
    public function validateCondition(int|string|float|array|\Stringable $columnDefinition): string
    {
        if ($columnDefinition instanceof \Stringable) {
            return "(".$columnDefinition.")";
        } else if (is_array($columnDefinition)) {
            return "(".implode(", ", $columnDefinition).")";
        } else {
            return $columnDefinition;
        }
    }
}