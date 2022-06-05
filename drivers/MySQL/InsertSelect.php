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
    protected bool $isIgnore=false;
    protected ?Set $onDuplicateKeyUpdate = null;

    /**
     * Sets statement as IGNORE, ignoring foreign key errors and duplicates
     */
    public function ignore(): void
    {
        $this->isIgnore = true;
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
        $ignore = ($this->isIgnore ? " IGNORE" : "");
        $output = "INSERT".$ignore." INTO ".$this->table." (".$this->getColumns().")"."\r\n".$this->getSelect();
        if ($this->onDuplicateKeyUpdate && !$this->onDuplicateKeyUpdate->isEmpty()) {
            $output .= "\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate;
        }
        return $output;
    }
}
