<?php

namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\Stringable;
use Lucinda\Query\Clause\Set;

/**
 * Encapsulates MySQL statement: INSERT {IGNORE} INTO {TABLE} SET {SET} ON DUPLICATE KEY UPDATE {UPDATES}
 */
class InsertSet implements \Stringable
{
    protected string $table;
    protected ?Set $set = null;
    protected bool $isIgnore=false;
    protected ?Set $onDuplicateKeyUpdate = null;

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
    public function ignore(): void
    {
        $this->isIgnore = true;
    }

    /**
     * Sets up SET clause.
     *
     * @param  array<string,string> $contents Sets condition group directly by column name and value
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
     * @param  array<string,string> $contents Sets condition group directly by column name and value
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
    public function __toString(): string
    {
        if (!$this->set || $this->set->isEmpty()) {
            throw new Exception("running set() method is mandatory");
        }

        $output = "INSERT ".($this->isIgnore ? "IGNORE" : "")." INTO ".$this->table." SET"."\r\n".$this->set;
        if ($this->onDuplicateKeyUpdate && !$this->onDuplicateKeyUpdate->isEmpty()) {
            $output .= "\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate;
        }
        return $output;
    }
}
