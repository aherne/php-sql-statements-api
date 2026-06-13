<?php

namespace Lucinda\Query\Vendor\PostgreSQL;

use Lucinda\Query\Clause\Fields;
use Lucinda\Query\Clause\Set;
use Lucinda\Query\Exception;
use Lucinda\Query\InsertSelect as DefaultInsertSelect;

/**
 * Encapsulates PostgreSQL INSERT INTO SELECT statement on top of SQL standard INSERT SELECT
 */
final class InsertSelect extends DefaultInsertSelect
{
    protected string $onConflictTarget = "";
    protected string $onConflictAction = "";
    protected ?Set $onConflictUpdate = null;
    protected ?Fields $returning = null;

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
     * Sets ON CONFLICT ON CONSTRAINT ... DO NOTHING clause.
     *
     * @param string $constraintName Name of unique/exclusion constraint
     */
    public function onConstraintDoNothing(string $constraintName): void
    {
        $this->onConflictTarget = " ON CONSTRAINT ".$constraintName;
        $this->onConflictAction = "DO NOTHING";
        $this->onConflictUpdate = null;
    }

    /**
     * Sets ON CONFLICT ON CONSTRAINT ... DO UPDATE SET clause.
     *
     * @param string $constraintName Name of unique/exclusion constraint
     * @param array<string,string> $contents Sets update clauses directly
     * @return Set Object to write further set clauses on.
     */
    public function onConstraintDoUpdate(string $constraintName, array $contents = []): Set
    {
        $set = new Set($contents);
        $this->onConflictTarget = " ON CONSTRAINT ".$constraintName;
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
        return parent::__toString().$this->getOnConflict().$this->getReturning();
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
