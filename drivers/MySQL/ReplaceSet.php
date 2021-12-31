<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Stringable;
use Lucinda\Query\Clause\Set;
use Lucinda\Query\Exception;

/**
 * Encapsulates MySQL statement: REPLACE INTO {TABLE} SET {SET}
 */
class ReplaceSet implements \Stringable
{
    protected string $table;
    protected ?Set $set = null;

    /**
     * Constructs a REPLACE INTO ... SET statement based on table name
     * 
     * @param string $table Name of table to replace into (including schema)
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Sets up SET clause.
     *
     * @param string[string] $contents Sets condition group directly by column name and value
     * @return Set Object to write further set clauses on.
     */
    public function set(array $contents = []): Set
    {
        $set = new Set($contents);
        $this->set = $set;
        return $set;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function __toString(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is mandatory");
        }

        return "REPLACE INTO ".$this->table." SET"."\r\n".
            $this->set;
    }
}
