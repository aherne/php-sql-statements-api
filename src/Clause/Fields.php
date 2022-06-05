<?php

namespace Lucinda\Query\Clause;

/**
 * Encapsulates SQL select fields clause.
 */
class Fields implements \Stringable
{
    /**
     * @var string[]
     */
    protected array $contents = [];

    /**
     * Class constructor.
     *
     * @param string[] $contents
     */
    public function __construct(array $contents = [])
    {
        $this->contents = $contents;
    }

    /**
     * Adds column to list.
     *
     * @param  string $columnName  Column name
     * @param  string $columnAlias Optional column alias
     * @return Fields
     */
    public function add(string $columnName, string $columnAlias = ""): Fields
    {
        $this->contents[]=($columnAlias ? new Alias($columnName, $columnAlias) : $columnName);
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
        if (!sizeof($this->contents)) {
            return $output;
        }

        foreach ($this->contents as $value) {
            $output .= $value.", ";
        }

        return substr($output, 0, -2);
    }
}
