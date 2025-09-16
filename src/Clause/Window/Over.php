<?php

namespace Lucinda\Query\Clause\Window;

use Lucinda\Query\Clause\OrderBy;

/**
 * Encapsulates SQL statement: OVER (PARTITION BY {EXPRESSION} ORDER BY {EXPRESSION})
 * TODO: add ROWS/RANGE BETWEEN ... PRECEDING/FOLLOWING
 */
class Over implements \Stringable
{
    private ?PartitionBy $partitionBy;
    private ?OrderBy $orderBy;

    /**
     * Constructs a OVER clause with optional PARTITION BY and ORDER BY clauses
     *
     * @param PartitionBy|null $partitionBy Optional PARTITION BY clause
     * @param OrderBy|null $orderBy Optional ORDER BY clause
     */
    public function __construct(?PartitionBy $partitionBy = null, ?OrderBy $orderBy = null)
    {
        $this->partitionBy = $partitionBy;
        $this->orderBy = $orderBy;
    }

    /**
     * Converts php data in statement or clauses classes to SQL
     *
     * @return string SQL that results from conversion
     */
    public function __toString(): string
    {
        $clause = "";
        if ($this->partitionBy) {
            $clause .= $this->partitionBy." ";
        }
        if ($this->orderBy) {
            $clause .= "ORDER BY ".$this->orderBy." ";
        }
        return trim($clause);
    }
}