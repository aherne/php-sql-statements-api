<?php

namespace Lucinda\Query\Clause;

use Lucinda\Query\Exception;
use Lucinda\Query\Select;
use Lucinda\Query\SelectGroup;
use Lucinda\Query\Stringable;

/**
 * Encapsulates SQL WITH clause (Common Table Expressions)
 */
class With implements Stringable
{
    private $recursive = false;
    private $selects = [];

    /**
     * Creates a WITH clause object
     *
     * @param bool $recursive Whether or not CTE is recursive (default: false)
     */
    public function __construct(bool $recursive = false)
    {
        $this->recursive = $recursive;
    }

    /**
     * Adds a CTE definition.
     *
     * @param string $name
     * @param Select|SelectGroup $select
     * @return $this
     */
    public function addSelect(string $name, Stringable $select): With
    {
        $this->selects[$name] = $select;
        return $this;
    }

    /**
     * Compiles SQL clause based on data collected in class fields.
     *
     * @return string SQL that results from conversion
     * @throws Exception
     */
    public function toString(): string
    {
        if (empty($this->selects)) {
            throw new Exception("At least one CTE must be defined in WITH clause.");
        }
        $output = "WITH " . ($this->recursive ? "RECURSIVE " : "");
        foreach ($this->selects as $name => $select) {
            $output .= $name . " AS (\r\n" . $select->toString() . "\r\n), "."\r\n";
        }
        return substr($output, 0, -4);
    }
}