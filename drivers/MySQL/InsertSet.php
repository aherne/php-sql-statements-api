<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\Stringable;
use Lucinda\Query\Clause\Set;

/**
 * Encapsulates MySQL statement: INSERT {IGNORE} INTO {TABLE} SET {SET} ON DUPLICATE KEY UPDATE {UPDATES}
 */
class InsertSet implements Stringable
{
    protected $table;
    protected $set;
    protected $isIgnore=false;
    protected $onDuplicateKeyUpdate;

    /**
     * Constructs a INSERT INTO ... SET statement based on table name
     * 
     * @param string $table Name of table to insert into (including schema)
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Sets statement as IGNORE, ignoring foreign key errors and duplicates
     */
    public function ignore()
    {
        $this->isIgnore = true;
        return $this;
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
     * Sets up ON DUPLICATE KEY UPDATE clause.
     *
     * @param string[string] $contents Sets condition group directly by column name and value
     * @return Set Object to write further set clauses on.
     */
    public function onDuplicateKeyUpdate(array $contents = []): Set
    {
        $set = new Set($contents);
        $this->onDuplicateKeyUpdate=$set;
        return $set;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function toString(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is mandatory");
        }

        return "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." SET"."\r\n".
            $this->set->toString().
            ($this->onDuplicateKeyUpdate && !$this->onDuplicateKeyUpdate->isEmpty()?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
    }
}
