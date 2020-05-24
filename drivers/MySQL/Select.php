<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Select as DefaultSelect;

/**
 * Encapsulates MySQL statement:
 * SELECT {FIELDS}
 * FROM {TABLE}
 * {TYPE} JOIN ON {CONDITION}
 * ...
 * WHERE {CONDITION}
 * GROUP BY {COLUMNS}
 * HAVING {CONDITION}
 * ORDER BY {ORDER_BY}
 * LIMIT {LIMIT}
 */
class Select extends DefaultSelect
{
    private $calcFoundRows = false;
    private $straightJoin = false;

    /**
     * Appends a SQL_CALC_FOUND_ROWS option to SELECT
     */
    public function setCalcFoundRows(): void
    {
        $this->calcFoundRows = true;
    }

    /**
     * Appends a STRAIGHT_JOIN option to SELECT
     */
    public function setStraightJoin(): void
    {
        $this->straightJoin = true;
    }

    /**
     * Gets query to retrieve found rows after a SELECT with SQL_CALC_FOUND_ROWS has ran
     *
     * @return string
     */
    public function getCalcFoundRows(): string
    {
        return "SELECT FOUND_ROWS()";
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output =
            "SELECT ".($this->isDistinct?" DISTINCT":"").($this->straightJoin?" STRAIGHT_JOIN":"").($this->calcFoundRows?" SQL_CALC_FOUND_ROWS":"").
            "\r\n".($this->columns?$this->columns->toString():"*").
            "\r\n"."FROM ".$this->table;
        if (sizeof($this->joins)>0) {
            foreach ($this->joins as $join) {
                $output .= "\r\n".$join->toString();
            }
        }
        $output .=
            ($this->where && !$this->where->isEmpty()?"\r\nWHERE ".$this->where->toString():"").
            ($this->groupBy && !$this->groupBy->isEmpty()?"\r\nGROUP BY ".$this->groupBy->toString():"").
            ($this->having && !$this->having->isEmpty()?"\r\nHAVING ".$this->having->toString():"").
            ($this->orderBy && !$this->orderBy->isEmpty()?"\r\nORDER BY ".$this->orderBy->toString():"").
            ($this->limit?"\r\nLIMIT ".$this->limit->toString():"");
        return $output;
    }
}
