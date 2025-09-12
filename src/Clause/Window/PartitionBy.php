<?php

namespace Lucinda\Query\Clause\Window;

use Lucinda\Query\Stringable;

/**
 * Encapsulates SQL statement: PARTITION BY {EXPRESSION}
 */
class PartitionBy implements Stringable
{
    private $columns = [];

    /**
     * Constructs a PARTITION BY clause with optional columns
     *
     * @param array $columns Optional list of columns to partition by
     */
    public function __construct(array $columns = [])
    {
        $this->columns = $columns;
    }

    /**
     * Adds column to partition by
     *
     * @param string $column Column name (including table/alias if needed)
     * @return PartitionBy Itself to allow chaining
     */
    public function add(string $column): PartitionBy
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * Converts php data in statement or clauses classes to SQL
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        return "PARTITION BY ".implode(", ", $this->columns);
    }
}