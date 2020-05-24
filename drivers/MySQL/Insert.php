<?php
namespace Lucinda\Query\Vendor\MySQL;

use Lucinda\Query\Insert as DefaultInsert;
use Lucinda\Query\Clause\Set;
use Lucinda\Query\Exception;

/**
 * Encapsulates MySQL statement: INSERT {IGNORE} INTO {TABLE} ({COLUMNS}) VALUES ({ROW}), ... ON DUPLICATE KEY UPDATE {UPDATES}
 */
class Insert extends DefaultInsert
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
        if (!$this->columns) {
            throw new Exception("running columns() method is mandatory");
        }
        if (!$this->rows) {
            throw new Exception("running values() is mandatory");
        }

        $output = "INSERT ".($this->isIgnore?"IGNORE":"")." INTO ".$this->table." (".$this->columns->toString().") VALUES"."\r\n";
        foreach ($this->rows as $row) {
            $output.=$row->toString().", ";
        }
        return substr($output, 0, -2).($this->onDuplicateKeyUpdate?"\r\n"."ON DUPLICATE KEY UPDATE ".$this->onDuplicateKeyUpdate->toString():"");
    }
}
