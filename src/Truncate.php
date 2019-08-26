<?php
namespace Lucinda\Query;

require_once("Exception.php");
require_once("Stringable.php");

/**
 * Encapsulates SQL statement: TRUNCATE TABLE {TABLE}
 */
class Truncate implements Stringable
{
    protected $table;

    /**
     * @param string $table Name of table to truncate (including schema)
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString()
    {
        return "TRUNCATE TABLE ".$this->table;
    }
}
