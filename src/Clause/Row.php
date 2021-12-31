<?php
namespace Lucinda\Query\Clause;

/**
 * Encapsulates SQL VALUES clause to be used by INSERT statements
 */
class Row implements \Stringable
{
    protected array $contents = [];

    /**
     * @param string[] $contents Sets list of values to write in columns directly
     */
    public function __construct(array $contents = [])
    {
        $this->contents = $contents;
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
        foreach ($this->contents as $value) {
            $output .= $value.", ";
        }
        return "(".substr($output, 0, -2).")";
    }
}
