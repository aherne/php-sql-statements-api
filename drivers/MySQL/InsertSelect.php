<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Exception;
use Lucinda\Query\InsertSelect as DefaultInsertSelect;
use Lucinda\Query\Clause\Set;

/**
 * Encapsulates MySQL statement: INSERT {IGNORE} INTO {TABLE} ({COLUMNS}) {SELECT} ON DUPLICATE KEY UPDATE {UPDATES}
 */
class InsertSelect extends DefaultInsertSelect
{
    protected $isIgnore=false;
    protected $onDuplicateKeyUpdate;

    /**
     * Sets statement as "IGNORE" (ignoring foreign key errors / duplicates)
     */
    public function ignore(): void
    {
        $this->isIgnore = true;
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
        if (!$this->columns || $this->columns->isEmpty()) {
            throw new Exception("running columns() method is required!");
        }
        if (!$this->select) {
            throw new Exception("running select() method is required!");
        }

        return  "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
            $this->select->toString().
            ($this->onDuplicateKeyUpdate && !$this->onDuplicateKeyUpdate->isEmpty()?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
    }
}
