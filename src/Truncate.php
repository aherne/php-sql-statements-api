<?php
namespace Lucinda\Query;

/**
 * Encapsulates SQL statement: TRUNCATE TABLE {TABLE}
 */
class Truncate implements Stringable
{
    protected $table;

    /**
     * Constructs a TRUNCATE statement based on table name
     * 
     * @param string $table Name of table to truncate (including schema)
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        return "TRUNCATE TABLE ".$this->table;
    }
}
