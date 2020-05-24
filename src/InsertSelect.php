<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Columns;

/**
 * Encapsulates SQL statement: INSERT INTO {TABLE} ({COLUMNS}) {SELECT}
 */
class InsertSelect implements Stringable
{
    protected $columns;
    protected $select;
    protected $table;

    /**
     * @param string $table Name of table to insert into (including schema)
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Sets columns that will be inserted into.
     *
     * @param string[] $columns Sets list of columns directly
     * @return Columns Objects to add further columns on.
     */
    public function columns(array $columns = array()): Columns
    {
        $fields = new Columns($columns);
        $this->columns = $fields;
        return $fields;
    }

    /**
     * Sets rows to insert based on a SELECT statement
     *
     * @param Stringable $select Instance of Select or SelectGroup.
     * @return Stringable  Instance of Select or SelectGroup.
     */
    public function select(Stringable $select): Stringable
    {
        $this->select=$select;
        return $select;
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

        return  "INSERT INTO ".$this->table." (".$this->columns->toString().")"."\r\n".
                $this->select->toString();
    }
}
