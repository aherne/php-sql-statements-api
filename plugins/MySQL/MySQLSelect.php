<?php
namespace Lucinda\Query;

require_once(dirname(dirname(__DIR__))."/src/Select.php");
require_once("clauses/MySQLCondition.php");

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
class MySQLSelect extends Select
{
    private $calcFoundRows;

    /**
     * Appends a SQL_CALC_FOUND_ROWS option to SELECT
     */
    public function setCalcFoundRows()
    {
        $this->calcFoundRows = true;
    }

    /**
     * Gets query to retrieve found rows after a SELECT with SQL_CALC_FOUND_ROWS has ran
     *
     * @return string
     */
    public function getCalcFoundRows()
    {
        return "SELECT FOUND_ROWS()";
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString()
    {
        $strOutput =
            "SELECT ".($this->isDistinct?" DISTINCT":"").($this->calcFoundRows?" SQL_CALC_FOUND_ROWS":"").
            "\r\n".($this->columns?$this->columns->toString():"*").
            "\r\n"."FROM ".$this->table;
        if (sizeof($this->joins)>0) {
            foreach ($this->joins as $join) {
                $strOutput .= "\r\n".$join->toString();
            }
        }
        $strOutput .=
            ($this->where && !$this->where->isEmpty()?"\r\nWHERE ".$this->where->toString():"").
            ($this->groupBy && !$this->groupBy->isEmpty()?"\r\nGROUP BY ".$this->groupBy->toString():"").
            ($this->having && !$this->having->isEmpty()?"\r\nHAVING ".$this->having->toString():"").
            ($this->orderBy && !$this->orderBy->isEmpty()?"\r\nORDER BY ".$this->orderBy->toString():"").
            ($this->limit?"\r\nLIMIT ".$this->limit->toString():"");
        return $strOutput;
    }
}
