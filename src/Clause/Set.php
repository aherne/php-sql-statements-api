<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Stringable;

/**
 * Encapsulates SQL SET clause
 */
class Set implements Stringable
{
    protected $contents = array();

    /**
     * @param string[string] $contents Sets condition group directly by column name and value
     */
    public function __construct(array $contents = array())
    {
        $this->contents = $contents;
    }

    /**
     * Sets a value of column by name.
     *
     * @param string $columnName Name of column to set
     * @param mixed $value Value of column set
     * @return Set
     */
    public function set(string $columnName, $value): Set
    {
        $this->contents[$columnName]=$value;
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
        foreach ($this->contents as $key=>$value) {
            $output .= $key." = ".$value.", ";
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
