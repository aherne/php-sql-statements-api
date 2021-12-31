<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Columns;

/**
 * Encapsulates SQL statement: INSERT INTO {TABLE} ({COLUMNS}) {SELECT}
 */
class InsertSelect implements \Stringable
{
    protected ?Columns $columns = null;
    protected ?\Stringable $select = null;
    protected string $table;

    /**
     * Constructs a INSERT INTO ... SELECT statement based on table name
     * 
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
    public function columns(array $columns = []): Columns
    {
        $fields = new Columns($columns);
        $this->columns = $fields;
        return $fields;
    }

    /**
     * Sets rows to insert based on a SELECT statement
     *
     * @param \Stringable $select Instance of Select or SelectGroup.
     */
    public function select(\Stringable $select): void
    {
        $this->select=$select;
    }

    /**
     * Compiles SQL statement based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception When statement could not be compiled due to incomplete class fields.
     */
    public function __toString(): string
    {
        if (!$this->columns || $this->columns->isEmpty()) {
            throw new Exception("running columns() method is required!");
        }
        if (!$this->select) {
            throw new Exception("running select() method is required!");
        }

        return  "INSERT INTO ".$this->table." (".$this->columns.")"."\r\n".
                $this->select;
    }
}
