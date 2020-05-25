<?php
namespace Lucinda\Query;

use Lucinda\Query\Clause\Columns;
use Lucinda\Query\Clause\Row;

/**
 * Encapsulates SQL statement: INSERT INTO {TABLE} ({COLUMNS}) VALUES ({ROW}), ...
 */
class Insert implements Stringable
{
    protected $columns;
    protected $rows = [];
    protected $table;

    /**
     * Constructs a INSERT INTO ... VALUES statement based on table name
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
     * @return Columns Object to add further columns on.
     */
    public function columns(array $columns = []): Columns
    {
        $fields = new Columns($columns);
        $this->columns = $fields;
        return $fields;
    }

    /**
     * Adds row to table via list of values to insert in columns
     *
     * @param string[] $updates Sets list of values to write in columns directly
     * @return Row Object to set further values on.
     */
    public function values(array $updates = []): Row
    {
        $row = new Row($updates);
        $this->rows[] = $row;
        return $row;
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
            throw new Exception("running columns() method is mandatory");
        }
        if (empty($this->rows)) {
            throw new Exception("running values() is mandatory");
        }

        $output = "INSERT INTO ".$this->table." (".$this->columns->toString().") VALUES\r\n";
        foreach ($this->rows as $row) {
            $output.=$row->toString().", ";
        }
        return  substr($output, 0, -2);
    }
}
