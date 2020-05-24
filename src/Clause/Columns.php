<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Stringable;

/**
 * Encapsulates column lists clause
 */
class Columns implements Stringable
{
    protected $contents = [];

    /**
     * @param string[] $contents Sets list of columns directly
     */
    public function __construct(array $contents = [])
    {
        $this->contents = $contents;
    }

    /**
     * Adds column to list.
     *
     * @param string $columnName Name of column to add
     * @return Columns Object to set further columns on.
     */
    public function add(string $columnName): Columns
    {
        $this->contents[]= $columnName;
        return $this;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output = "";
        if (!sizeof($this->contents)) {
            return $output;
        }

        foreach ($this->contents as $value) {
            $output .= $value.", ";
        }

        return substr($output, 0, -2);
    }

    /**
     * Checks if clause is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return sizeof($this->contents) == 0;
    }
}
