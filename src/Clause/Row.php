<?php
namespace Lucinda\Query\Clause;

use Lucinda\Query\Stringable;

/**
 * Encapsulates SQL VALUES clause to be used by INSERT statements
 */
class Row implements Stringable
{
    protected $contents = [];

    /**
     * @param string[] $contents Sets list of values to write in columns directly
     */
    public function __construct(array $contents = [])
    {
        $this->contents = $contents;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     */
    public function toString(): string
    {
        $output = "";
        foreach ($this->contents as $value) {
            $output .= $value.", ";
        }
        return "(".substr($output, 0, -2).")";
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
