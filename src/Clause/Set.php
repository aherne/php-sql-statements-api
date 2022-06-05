<?php

namespace Lucinda\Query\Clause;

use Lucinda\Query\Select;
use Lucinda\Query\SelectGroup;

/**
 * Encapsulates SQL SET clause
 */
class Set implements \Stringable
{
    /**
     * @var array<string,mixed>
     */
    protected array $contents = [];

    /**
     * @param array<string,string> $contents Sets condition group directly by column name and value
     */
    public function __construct(array $contents = [])
    {
        $this->contents = $contents;
    }

    /**
     * Sets a value of column by name.
     *
     * @param  string                              $columnName Name of column to set
     * @param  int|string|float|Select|SelectGroup $value      Value of column set
     * @return Set
     */
    public function set(string $columnName, int|string|float|Select|SelectGroup $value): Set
    {
        $this->contents[$columnName]=$value;
        return $this;
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

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function __toString(): string
    {
        $output = "";
        foreach ($this->contents as $key=>$value) {
            $output .= $key." = ".(is_object($value) ? "(".$value.")" : $value).", ";
        }
        return substr($output, 0, -2);
    }
}
