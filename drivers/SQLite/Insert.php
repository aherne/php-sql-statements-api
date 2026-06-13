<?php

namespace Lucinda\Query\Vendor\SQLite;

use Lucinda\Query\Clause\Fields;
use Lucinda\Query\Clause\Set;
use Lucinda\Query\Exception;
use Lucinda\Query\Insert as DefaultInsert;
use Lucinda\Query\Vendor\SQLite\Operator\Conflict;

/**
 * Encapsulates SQLite INSERT INTO VALUES statement on top of SQL standard INSERT
 */
final class Insert extends DefaultInsert
{
    protected ?Conflict $conflictAlgorithm = null;
    protected string $onConflictTarget = "";
    protected string $onConflictAction = "";
    protected ?Set $onConflictUpdate = null;
    protected ?Fields $returning = null;

    /**
     * Sets INSERT OR ROLLBACK/ABORT/FAIL/IGNORE/REPLACE conflict algorithm.
     *
     * @param Conflict $algorithm Conflict resolution algorithm
     */
    public function or(Conflict $algorithm): void
    {
        $this->conflictAlgorithm = $algorithm;
    }

    /**
     * Sets INSERT OR IGNORE.
     */
    public function orIgnore(): void
    {
        $this->or(Conflict::IGNORE);
    }

    /**
     * Sets INSERT OR REPLACE.
     */
    public function orReplace(): void
    {
        $this->or(Conflict::REPLACE);
    }

    /**
     * Sets ON CONFLICT ... DO NOTHING clause.
     *
     * @param string[] $columns Optional conflict target columns
     */
    public function onConflictDoNothing(array $columns = []): void
    {
        $this->onConflictTarget = $this->getConflictTarget($columns);
        $this->onConflictAction = "DO NOTHING";
        $this->onConflictUpdate = null;
    }

    /**
     * Sets ON CONFLICT ... DO UPDATE SET clause.
     *
     * @param string[] $columns Conflict target columns
     * @param array<string,string> $contents Sets update clauses directly
     * @return Set Object to write further set clauses on.
     */
    public function onConflictDoUpdate(array $columns, array $contents = []): Set
    {
        $set = new Set($contents);
        $this->onConflictTarget = $this->getConflictTarget($columns);
        $this->onConflictAction = "DO UPDATE SET";
        $this->onConflictUpdate = $set;
        return $set;
    }

    /**
     * Sets RETURNING clause.
     *
     * @param string[] $columns Sets list of expressions directly
     * @return Fields Object to add further expressions on.
     */
    public function returning(array $columns = []): Fields
    {
        $fields = new Fields($columns);
        $this->returning = $fields;
        return $fields;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function __toString(): string
    {
        $output = "INSERT".$this->getConflictAlgorithm()." INTO ".$this->table." (".$this->getColumns().") VALUES\r\n".$this->getRows();
        return $output.$this->getOnConflict().$this->getReturning();
    }

    protected function getConflictAlgorithm(): string
    {
        return ($this->conflictAlgorithm ? " OR ".$this->conflictAlgorithm->value : "");
    }

    protected function getConflictTarget(array $columns): string
    {
        return ($columns ? " (".implode(", ", $columns).")" : "");
    }

    protected function getOnConflict(): string
    {
        if (!$this->onConflictAction) {
            return "";
        }
        $output = "\r\nON CONFLICT".$this->onConflictTarget." ".$this->onConflictAction;
        if ($this->onConflictUpdate && !$this->onConflictUpdate->isEmpty()) {
            $output .= " ".$this->onConflictUpdate;
        }
        return $output;
    }

    protected function getReturning(): string
    {
        return ($this->returning && !$this->returning->isEmpty() ? "\r\nRETURNING ".$this->returning : "");
    }
}
